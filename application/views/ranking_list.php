<div class="smx-ui-title">
    <button type="button" class="hamburger animated fadeInLeft is-closed" data-toggle="offcanvas">
        <span class="hamb-top"></span>
        <span class="hamb-middle"></span>
        <span class="hamb-bottom"></span>
    </button>
    <h2 id="smx-title">RANKING LIST</h2>
</div>


<div class="container">

    <table class="table table-dark" data-sorting="true">
        <thead>
        <th>Player</th>
        <th data-type="number">Basic</th>
        <th data-type="number">Easy</th>
        <th data-type="number">Hard</th>
        <th data-type="number">Wild</th>
        <th data-type="number">Dual</th>
        <th data-type="number">Full</th>
        </thead>
        <tbody>
        <?php foreach ($rankings as $user):
            $user_info = $this->data->user_info_db($user['user_id']);
            ?>
            <tr>
                <td><img style="border-radius: 100px; margin-right: 20px;"
                         src="https://data.stepmaniax.com/<?= $user_info['picture_path'] ?>"
                         width="35px"> <a style="color: white; text-decoration: underline"
                                          href="<?= base_url('player/' . $user_info['id']) ?>"> <?= $user_info['username'] ?></a>
                </td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'basic')) ?></td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'easy')) ?></td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'hard')) ?></td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'wild')) ?></td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'dual')) ?></td>
                <td><?= number_format($this->data->getRank($user['user_id'], 'full')) ?></td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>


</div>

<script>
    jQuery(function ($) {
        $('.table').footable({
            "expandFirst": false,
            "showToggle": false
        });
    });
</script>