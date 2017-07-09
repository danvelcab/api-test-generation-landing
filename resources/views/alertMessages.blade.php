@if(session('message') != null)
    <script type="text/javascript">
        new Noty({
            timeout: 5000,
            layout: 'topCenter',
            type: 'success',
            text: '<?php echo session('message')?>',
        }).show();
    </script>
@endif
@if(session('error') != null)
    <script type="text/javascript">
        new Noty({
            timeout: 5000,
            layout: 'topCenter',
            type: 'error',
            text: '<?php echo session('error')?>',
        }).show();
    </script>
@endif
@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <script type="text/javascript">
            new Noty({
                timeout: 5000,
                layout: 'topCenter',
                type: 'error',
                text: '<?php echo $error?>',
            }).show();
        </script>
    @endforeach
@endif