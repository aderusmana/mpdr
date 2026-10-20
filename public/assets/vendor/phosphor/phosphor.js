// Phosphor Icons - High performance lazy loader (only bold & duotone, deferred)
(function() {
    function loadPhosphor() {
        var head = document.head || document.getElementsByTagName("head")[0];
        ['bold', 'duotone'].forEach(function(weight) {
            if (!document.getElementById('ph-' + weight)) {
                var link = document.createElement("link");
                link.id = 'ph-' + weight;
                link.rel = "stylesheet";
                link.type = "text/css";
                link.href = "https://unpkg.com/@phosphor-icons/web@2.0.3/src/" + weight + "/style.css";
                head.appendChild(link);
            }
        });
    }

    if (window.requestIdleCallback) {
        requestIdleCallback(loadPhosphor, { timeout: 2000 });
    } else {
        setTimeout(loadPhosphor, 1200);
    }
})();

