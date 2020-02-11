(function (d, w, $) {
    var c = d.className;
    d.className = c + (c && ' ') + 'iphorm-js';
    w.iPhorm = {
        preloadedImages: [],
        preload: function (images, prefix) {
            for (var i = 0; i < images.length; i++) {
                var elem = document.createElement('img');
                elem.src = prefix ? prefix + images[i] : images[i];
                w.iPhorm.preloadedImages.push(elem);
            }
        },
        instance: null,
        logic: {},
        recaptchas: [],
        recaptchaIds: []
    };

    w.iPhormRecaptchaLoaded = function () {
        if (!w.grecaptcha) return;

        for (var i = 0; i < w.iPhorm.recaptchas.length; i++) {
            var recaptcha = w.iPhorm.recaptchas[i];
            var repcaptchaId = grecaptcha.render(recaptcha.uniqueId, recaptcha.config);
            w.iPhorm.recaptchaIds.push(repcaptchaId);
        }
    };
})(document.documentElement, window);