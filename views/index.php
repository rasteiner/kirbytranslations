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
          <?php foreach ($keys as $key): ?>
            <tr>
              <td style="font-style: italic; border-bottom: 1px solid grey"><?php echo esc($key); ?></td>
              <?php foreach ($languages as $lang): ?>
                <?php $value = $translations[$lang][$key]; $value = str_replace("\"", "&#34;", $value); ?>
                <td><input style="width: 100%" type="text" data-lang="<?php echo $lang ?>" data-key="<?php echo $key ?>" value="<?php echo $value ?>"></td>
              <?php endforeach ?>
            </tr>
          <?php endforeach ?>
        </table>
        <input type="hidden" name="csrf" value="<?php echo panel()->csrf() ?>">

        <fieldset class="fieldset buttons buttons-centered">
          <input class="btn btn-rounded btn-submit" type="submit" value="<?php echo l::get('save'); ?>">
        </fieldset>
      </form>
    </div>
  </div>

</div>

<script type="text/javascript">
  $('#form-static-translation').submit(function(e) {
    e.preventDefault();
    var data = {};

    $(this).find('input[data-key][data-lang]').each(function() {
      var $this = $(this);
      var lang = $this.data('lang');
      var key = $this.data('key');

      if(undefined === data[lang]) {
        data[lang] = {};
      }

      data[lang][key] = $(this).val();
    });

    $.post(<?php echo json_encode(panel()->urls()->index . '/translations') ?>, {
      jsondata: JSON.stringify(data),
    }).then(function(response){
      console.log(response);
    });

  });
</script>
