<div class="d-inline">
    <button type="submit" id="button-submit" class="btn btn-primary">{{ $text }}</button>
    <button id="button-submit-loading" class="btn btn-primary d-none" type="button" disabled>
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading...
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('{{ $formId }}');

        form.addEventListener('submit', function(e) {
            document.getElementById('button-submit').classList.add('d-none');
            document.getElementById('button-submit-loading').classList.remove('d-none');
        });

        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.navigation.type == 2)) {
                document.getElementById('button-submit').classList.remove('d-none');
                document.getElementById('button-submit-loading').classList.add('d-none');
            }
        });
    });
</script>
