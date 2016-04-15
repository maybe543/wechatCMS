(function ($) {

    var loadImg = function (url, fn) {
        var img = new Image();
        img.src = url;
        if (img.complete) {
            fn.call(img);
        } else {
            img.onload = function () {
                fn.call(img);
                img.onload = null;
            };
        };
    };

    $.fn.imgAutoSize = function (padding) {
        var maxWidth = this.innerWidth() - (padding || 0);
        var maxHeight = this.innerHeight() - (padding || 0);
        return this.find('img').each(function (i, img) {
            loadImg(this.src, function () {
                var height = 0,width = 0;
                if (this.width > maxWidth) {
                    height = maxWidth / this.width * this.height;
                    width = maxWidth;
                    if (height < maxHeight) {
                        height = maxHeight;
                        width = maxHeight / this.height * this.width;
                    }
                    img.width = width;
                    img.height = height;
                }
            });
        });
    };

})(jQuery);