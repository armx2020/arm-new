<div class="w-[20rem] lg:w-[25rem] xl:w-[35rem] mt-3 flex">
    <select name="entity" style="border-color:rgb(209 213 219);width:100%" id="entity">
        <option></option>
    </select>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        if ($("#entity").length > 0) {
            $("#entity").select2({
                placeholder: "Поиск по справочнику",
                language: {
                    noResults: function() {
                        return "Ничего не найдено";
                    },
                    searching: function() {
                        return "Идет поиск...";
                    },
                    errorLoading: function() {
                        return "Не удалось загрузить результаты";
                    }
                },
                ajax: {
                    url: "{{ route('entities') }}",
                    type: "GET",
                    delay: 250,
                    dataType: 'json',
                    data: function(params) {
                        return {
                            query: params.term || '',
                            page: params.page || 1,
                        };
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
        }

        $('#entity').on('select2:select', function(e) {
            var data = e.params.data;
            window.location.href = data.url; // Переход по ссылке
        });
    });
</script>

<style>
    .select2-container--default .select2-selection--single {
        background-color: #F5F7FA;
        border: 1px solid #F5F7FA;
        border-radius: 4px;
    }

    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #747678;
        font-size: 1rem; /* 18px */
        line-height: 1.5rem; /* 28px */
        font-weight: 500;
    }
</style>
