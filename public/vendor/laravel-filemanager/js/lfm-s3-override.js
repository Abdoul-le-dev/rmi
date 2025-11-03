// public/js/lfm-s3-override.js
(function() {
    // Override de la fonction de preview
    if (typeof window.lfm !== 'undefined') {
        const originalSetItems = window.lfm.setItems;
        
        window.lfm.setItems = function(items) {
            // Transformer les chemins en URLs temporaires
            items = items.map(item => {
                if (item.url) {
                    item.url = '/media/preview/' + item.url.replace(/^\/+/, '');
                    item.thumb_url = item.url;
                }
                return item;
            });
            
            return originalSetItems.call(this, items);
        };
    }
    
    // Override pour le preview des images
    $(document).on('click', '.lfm-item', function() {
        const $img = $(this).find('img');
        if ($img.length) {
            const src = $img.attr('src');
            // Régénérer l'URL temporaire si nécessaire
            if (!src.includes('X-Amz-Expires')) {
                $img.attr('src', src + '?t=' + Date.now());
            }
        }
    });
})();