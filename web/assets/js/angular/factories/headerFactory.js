app.factory('HeaderFactory', function() {

    var _pageTitle = '';
    var HeaderFactory = {};

    /**
     * Set the page title
     * 
     * @param string $title
     */
    HeaderFactory.setTitle = function($title){
        _pageTitle = $title;
    }

    /**
     * Get the page  title
     *
     * @return string
     */
    HeaderFactory.getTitle = function(){
        return _pageTitle;
    }

    return HeaderFactory;

});
