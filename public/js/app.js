$(document).ready(function () {

    var searchForm = $('#SearchForm');

    searchForm.submit(function () {
        var form = $(this);
        var data = form.serialize();
        var url = form.attr("action");
        var container = $('#Payments');

        $.get(url, data, function (response) {
            container.html(response);

            container.find("form").submit(function () {
                var form = $(this);
                var resultString = '';
                var data = form.find('input[type="text"]').each(function () {
                    resultString = resultString + $(this).val();
                });
                resultString = resultString + 'some_secret';
                form.find('input[name="md5"]').val(md5(resultString));
            });
        });
        return false;
    });

    searchForm.find('input[name="name"]').autocomplete({
        source: "/user/find/",
        minLength: 2,
        select: function (event, ui) {
            var nameInput = searchForm.find('input[name="name"]');
            var idInput = searchForm.find('input[name="id"]');
            if(ui.item) {
                nameInput.val(ui.item.label);
                idInput.val(ui.item.value);
                searchForm.submit();
            } else {
                nameInput.val('');
                idInput.val('');
            }
            return false;
            /*console.log(ui.item ?
            "Selected: " + ui.item.value + " aka " + ui.item.id :
            "Nothing selected, input was " + this.value);*/
        }
    });
});
