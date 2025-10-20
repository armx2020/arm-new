@props(['selectedEntity' => null])

<div class="col-span-6" id="entity_div" wire:ignore>
    <label for="entity" class="text-sm font-medium text-gray-900 block mb-2">Сущность</label>
    <select name="entity" class="w-full" id="entity" wire:model.live="selectedType" required>
        @if (isset($selectedEntity))
            <option value="{{ $selectedEntity->id }}"> {{ $selectedEntity->name }} ({{ $selectedEntity->type->name }})</option>
        @else
            <option value=''>-- выбор сущности --</option>
        @endif
    </select>
</div>

<script type="text/javascript">
    if ($("#entity").length > 0) {
        $("#entity").select2({
            ajax: {
                url: " {{ route('admin.get-entity') }}",
                type: "get",
                delay: 250,
                dataType: 'json',
                data: function(params) {
                    var query = {
                        query: params.term || '',
                        page: params.page || 1,
                        "_token": "{{ csrf_token() }}",
                    };

                    return query;
                },
                processResults: function(response, params) {
                    params.page = params.page || 1;
                    return {
                        results: response.results,
                        pagination: {
                            more: response.pagination.more
                        }
                    };
                },
                cache: true
            }
        });
        $('#entity').on('change', function(e) {
            // let elementName = $(this).attr('id');
            // var data = $(this).select2("val");
            @this.set('selectedType', e.target.value);
        });
    }
</script>
