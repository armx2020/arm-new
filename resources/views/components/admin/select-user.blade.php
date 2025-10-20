@props(['selectedUser' => null])

<div class="col-span-6" id="user_div" wire:ignore>
    <div class="flex justify-between">
        <label for="user" class="text-sm font-medium text-gray-900 mb-2">Пользователь</label>
        @if (isset($selectedUser))
            <a id="userLink" href="{{ route('admin.user.edit', ['user' => $selectedUser->id]) }}"
                class="underline underline-offset-2 text-blue-600 hover:text-blue-500" target="blank">для
                перехода в карточку пользователя, нажмите сюда</a>
        @else
            <a id="userLink" href="#"
                class="underline underline-offset-2 text-blue-600 hover:text-blue-500 hidden" target="blank">для
                перехода в карточку пользователя, нажмите сюда</a>
        @endif
    </div>
    <select name="user" class="w-full" id="user">
        @if (isset($selectedUser))
            <option value="{{ $selectedUser->id }}" data-url="{{ route('admin.user.edit', ['user' => 1]) }}">
                {{ $selectedUser->firstname }} {{ $selectedUser->phone }}</option>
        @else
            <option value=''>Введите имя или номер</option>
        @endif
    </select>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("#user").select2({
            //    tags: true,
            ajax: {
                url: "{{ route('admin.get-user') }}",
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
            },
        });

        $('#user').on('change', function() {

            const selectedOption = $(this).val();

            if (!isNaN(selectedOption)) {
                const userLink = $('#userLink');

                if (selectedOption) {
                    userLink.attr('href', 'https://vsearmyane.ru/admin/user/' + selectedOption +
                        '/edit');
                    userLink.show();
                } else {
                    userLink.hide();
                }
            }

        });

        const selectedOption = $('#user').val();

        const userLink = $('#userLink');

        if (selectedOption) {
            userLink.attr('href', 'https://vsearmyane.ru/admin/user/' + selectedOption + '/edit');
            userLink.show();
        } else {
            userLink.hide();
        }
    });
</script>

<style>
    .select2-container--default .select2-selection--single {
        background-color: rgb(249 250 251 / var(--tw-bg-opacity));
        border-color: rgb(209 213 219);
        border-radius: 0.5rem;
    }
</style>
