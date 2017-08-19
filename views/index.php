<div class="bars cf">

  <div class="">
    <div class="section">

      <h2 class="hgroup hgroup-single-line hgroup-compressed cf"><span class="hgroup-title">Translations</span></h2>


      <form id="form-static-translation">
        <table style="width: 100%">
          <tr>
            <th>Key</th>
            <?php foreach ($languages as $lang): ?>
              <th><?php echo html($lang); ?></th>
            <?php endforeach ?>
          </tr>
          <?php foreach ($translations as $key => $strings): ?>
            <tr>
              <td style="font-style: italic; border-bottom: 1px solid grey"><?php echo esc($key); ?></td>
              <?php foreach ($languages as $lang): ?>
                <?php $value = $strings[$lang]; $value = str_replace("\"", "&#34;", $value); ?>
                <td><input style="width: 100%" type="text" name="<?php echo esc("trans__${lang}__${key}"); ?>" value="<?php echo $value ?>"></td>
              <?php endforeach ?>
            </tr>
          <?php endforeach ?>
        </table>
        <input type="hidden" name="csrf" value="<?php echo panel()->csrf() ?>">

        <fieldset class="fieldset buttons buttons-centered">
          <input class="btn btn-rounded btn-submit" type="submit" value="<?php echo l::get('save'); ?>">
        </fieldset>
      </form>

      <form method="POST" action="<?php echo panel()->urls()->index . '/translations' ?>" id="form-static-translation-json">
          <input type="hidden" name="csrf" value="<?php echo panel()->csrf() ?>">
          <input type="hidden" name="jsondata" value="">
      </form>

    </div>
  </div>

</div>

<script type="text/javascript">
// https://stackoverflow.com/questions/1255948/post-data-in-json-format
(function() {
    var form = document.getElementById("form-static-translation");
    var jsonform = document.getElementById("form-static-translation-json");

    form.onsubmit = function(e) {
        // stop the regular form submission
        e.preventDefault();

        // collect the form data while iterating over the inputs
        var data = {};
        for (var i = 0, ii = form.length; i < ii; ++i) {
          var input = form[i];
          if (input.name) {
            data[input.name] = input.value;
          }
        }

        jsonform["jsondata"].value = JSON.stringify(data);
        jsonform.submit();
      };
})();
</script>
