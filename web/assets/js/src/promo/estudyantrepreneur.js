(function($) {
    $('#ddown-school').on('change', function() {
        var $this = $(this);
        var selectedDataId = $this.find(':selected').data('id');
        $('.display-none').hide();
        $('#'+selectedDataId).show();
    });
})(jQuery);
