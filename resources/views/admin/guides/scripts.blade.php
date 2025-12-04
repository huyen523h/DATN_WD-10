<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-add-row]').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetSelector = this.getAttribute('data-target');
                const templateSelector = this.getAttribute('data-template');
                const container = document.querySelector(targetSelector);
                const template = document.querySelector(templateSelector);

                if (!container || !template) return;

                const clone = template.innerHTML;
                const index = container.querySelectorAll('.repeat-row').length;
                const html = clone.replace(/__INDEX__/g, index);
                container.insertAdjacentHTML('beforeend', html);
            });
        });

        document.addEventListener('click', function(event) {
            if (event.target.matches('[data-remove-row]')) {
                const row = event.target.closest('.repeat-row');
                if (row) {
                    row.remove();
                }
            }
        });
    });
</script>

