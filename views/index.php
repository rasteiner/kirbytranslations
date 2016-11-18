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
          <?php $stats = array('missing translation' => 0, 'same translation' => 0); ?>

          <?php foreach ($translations as $key => $strings): ?>
            <tr>

              <?php
                  $keyStyle = "";
                  $values = array();
                  foreach ($languages as $lang) {
                      $values[$strings[$lang]] = 1;
                  }

                  if (count($values) == count($languages)) {
                      // each language is different
                      $keyStyle = "background-color: lightgreen;";
                  } else {
                      // at least two languages share the same translated value,
                      // which indicates missing translation

                      if (array_key_exists('', $values)) {
                          // a translation value is completely missing
                          $keyStyle = "background-color: firebrick;";
                          $stats['missing translation']++;
                      } else {
                          $keyStyle = "background-color: lightyellow;";
                          $stats['same translation']++;
                      }
                  }
              ?>

              <td style="font-style: italic; border-bottom: 1px solid grey; <?php echo $keyStyle; ?>"><?php echo esc($key); ?></td>
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

      <h2 class="hgroup hgroup-single-line hgroup-compressed cf"><span class="hgroup-title">Statistics</span></h2>
      <ul>
        <li><span style="background-color: lightyellow">Keys with same translations: <?php echo $stats['same translation']; ?></span>
            Two or more languages share the same translated value. This could be correct, but it could also mean, that the
            translation was simply overlooked.
        </li>
        <li><span style="background-color: firebrick">Keys with missing translations: <?php echo $stats['missing translation']; ?></span>
        </li>
      </ul>
    </div>
  </div>

</div>
