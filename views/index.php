<div class="bars cf">

  <div class="">
    <div class="section">

      <h2 class="hgroup hgroup-single-line hgroup-compressed cf"><span class="hgroup-title">Translations</span></h2>


      <form method="POST" action="<?php echo panel()->urls()->index . '/translations' ?>" class="form">
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

      <h2 class="hgroup hgroup-single-line hgroup-compressed cf"><span class="hgroup-title">Import / Export</span></h2>
      <div class="section">
        <a href="<?php echo panel()->urls()->index . '/translations/csv' ?>" class="btn btn-rounded">Download as CSV</a>
      </div>
      <div class="section">
        <form method="POST" enctype="multipart/form-data" action="<?php echo panel()->urls()->index . '/translations/csv' ?>" class="form-upload-translations">
          <input class="btn btn-rounded btn-submit" type="submit" value="Upload">
          <input type="file" name="file" accept="text/csv">
          <input type="hidden" name="csrf" value="<?php echo panel()->csrf() ?>">
        </form>
      </div>
    </div>
  </div>

</div>
