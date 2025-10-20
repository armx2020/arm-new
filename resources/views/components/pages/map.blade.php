  <div class="mb-8">

      @php
          $zoom = 8;

          if (!isset($lat) && !isset($lon)) {
              $lat = 55.755819;
              $lon = 37.617644;
              $zoom = 7;
          }
      @endphp

      <div id="geolocation-status"
          class="mb-4 flex basis-full bg-yellow-100 rounded-lg px-6 py-2 text-xs lg:text-base text-green-700 w-full hidden"
          role="alert" style="max-height:64px;">
      </div>

      <div id="map" style="height: 200px; width: 100%;"></div>
      <style>
          .ymaps-2-1-79-gototech {
              display: none !important;
          }

          .ymaps-2-1-79-copyrights-promo {
              display: none !important;
          }
      </style>

      <script src="https://api-maps.yandex.ru/2.1/?apikey={{ config('services.yandex.geocoder_key') }}&lang=ru_RU"></script>
      <script type="text/javascript">
          $(document).ready(function() {
              ymaps.ready(function() {
                  var myMap = new ymaps.Map('map', {
                          center: [{{ $lat }}, {{ $lon }}],
                          zoom: {{ $zoom }},
                          controls: ['zoomControl', 'geolocationControl', 'fullscreenControl'],
                      }, {
                          searchControlProvider: 'yandex#search'
                      }),
                      clusterer = new ymaps.Clusterer({
                          preset: 'islands#invertedVioletClusterIcons',
                          groupByCoordinates: false,
                          clusterDisableClickZoom: true,
                          clusterHideIconOnBalloonOpen: false,
                          geoObjectHideIconOnBalloonOpen: false
                      }),
                      /**
                       * Модифицированная функция для использования данных из $entities
                       */
                      getEntityData = function(entity) {
                          return {
                              balloonContentHeader: '<font size=3><b>' + entity.name + '</b></font>',
                              balloonContentBody: '<div style="padding: 5px; max-width: 300px;">' +
                                  '<a target="_blank" href="https://yandex.ru/maps/?pt=' + entity.lon + ',' +
                                  entity.lat + '&z=17&l=map">' + (entity.address ?
                                      '<p><strong>Адрес:</strong> ' + entity.address + '</p>' :
                                      '') +
                                  'Открыть в Яндекс Картах</a>' +
                                  '</div>',
                              balloonContentFooter: '<font size=1>ID объекта: ' + entity.id + '</font>',
                              clusterCaption: entity.name
                          };
                      },
                      /**
                       * Функция возвращает опции метки (можно кастомизировать)
                       */
                      getEntityOptions = function() {
                          return {
                              preset: 'islands#violetIcon',
                              balloonCloseButton: true,
                              hideIconOnBalloonOpen: false
                          };
                      },
                      /**
                       * Массив координат из ваших сущностей
                       */

                      @php
                          $isEntityHasFromThisRegion = false;

                          foreach ($entities as $entity) {
                              if ($entity->region->transcription == $region && $region !== 'russia') {
                                  $isEntityHasFromThisRegion = true;
                              }
                          }
                      @endphp

                  entities = [
                          @foreach ($entities as $entity)

                              @if ($isEntityHasFromThisRegion && $entity->region->transcription !== $region && $region !== 'russia')
                                  @break
                              @endif

                              @if ($entity->coordinates)
                                  {
                                      id: {{ $entity->id }},
                                      name: '{{ addslashes($entity->name) }}',
                                      address: '{{ $entity->address ? addslashes($entity->address) : '' }}',
                                      lat: {{ $entity->lat }},
                                      lon: {{ $entity->lon }}
                                  },
                              @else
                                  {
                                      id: {{ $entity->id }},
                                      name: '{{ addslashes($entity->name) }}',
                                      address: '{{ $entity->city->name ? addslashes($entity->name) : '' }}',
                                      lat: {{ $entity->city->lat }},
                                      lon: {{ $entity->city->lon }}
                                  },
                              @endif
                          @endforeach
                      ],
                      geoObjects = [];
                  console.log(entities)
                  /**
                   * Создаем метки для каждой сущности
                   */
                  for (var i = 0, len = entities.length; i < len; i++) {
                      geoObjects[i] = new ymaps.Placemark(
                          [entities[i].lat, entities[i].lon],
                          getEntityData(entities[i]),
                          getEntityOptions()
                      );
                  }

                  /**
                   * Настройки кластеризатора
                   */
                  clusterer.options.set({
                      gridSize: 80,
                      clusterDisableClickZoom: true,
                      clusterOpenBalloonOnClick: true
                  });

                  /**
                   * Добавляем метки в кластеризатор
                   */
                  clusterer.add(geoObjects);
                  myMap.geoObjects.add(clusterer);

                  /**
                   * Центрируем карту по всем объектам
                   */
                  if (geoObjects.length > 0) {
                      if (geoObjects.length === 1) {
                          // Если только один объект, устанавливаем zoom = 7
                          myMap.setCenter([entities[0].lat, entities[0].lon], 7);
                      } else {
                          var bounds = clusterer.getBounds();

                          // Расширяем границы с отступами (в градусах, а не в пикселях!)
                          var paddingLat = 0.05; // ±0.05 градуса широты (~5 км)
                          var paddingLon = 0.1; // ±0.1 градуса долготы (~7 км в средней полосе)

                          var paddedBounds = [
                              [bounds[0][0] - paddingLat, bounds[0][1] - paddingLon], // Юго-западный угол
                              [bounds[1][0] + paddingLat, bounds[1][1] +
                                  paddingLon
                              ] // Северо-восточный угол
                          ];
                          // Если несколько объектов, используем автоматическое определение границ
                          myMap.setBounds(paddedBounds, {
                              checkZoomRange: true,
                              zoomMargin: 3,
                              padding: [1000, 100]
                          });
                      }
                  }

              });
          });
      </script>
  </div>
