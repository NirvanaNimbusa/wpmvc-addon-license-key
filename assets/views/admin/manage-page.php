<?php
/**
 * License key manage page.
 *
 * @author Cami Mostajo
 * @package WPMVC\Addons\LicenseKey
 * @license MIT
 * @version 2.0.0
 */
?>
<style type="text/css">
.panel {
    background-color: #fff;
    border: 1px solid #eee;
    padding: 20px;
    margin: 20px 0;
}
table.short_table {
    width: 100%;
}
table.short_table th {
    text-align: left;
}
table.short_table input {
    width: 100%;
    font-size: 20px;
}
.actions {
    overflow: hidden;
    position: relative;
    margin-top: 10px;
}
.actions button {
    float: right;
    margin-left: 10px !important;
}
.actions button.remove {
    float: right;
    color: #fff;
    border-color: #ff3838;
    background: #F44336;
    box-shadow: 0 1px 0 #ab9595;
}
.actions button.remove:hover {
    background: #E53935;
    border-color: #b37b7b;
    color: #fff1f0;
}
code.the-key {
    width: 100%;
    padding: 6px 0;
    margin-bottom: 10px;
    color: #00008b;
    font-size: 20px;
}
span.status-valid {
    color: #4CAF50;
    font-weight: 600;
}
span.status-invalid {
    color: #F44336;
    font-weight: 600;
}
dl.response-errors dt {
    font-weight: 500;
    font-style: italic;
}
ul.errors {
    list-style: square;
    margin: 0;
    padding: 0;
    color: #D32F2F;
}
@media screen and (max-width:440px) {
    code.the-key {
        width: 100%;
        padding: 6px 0;
        margin-bottom: 10px;
        color: #00008b;
        font-size: 12px;
    }
}
</style>
<div class="wrap addon-license-key <?= $ref ?>-license-key">
    <h1 class="wp-heading-inline">
        <?= sprintf(
            __( 'Manage License Key for %s <strong>%s</strong>', 'wpmvc-addon-license-key' ),
            __( $main->config->get('type'), 'wpmvc-addon-license-key' ),
            $main->config->get('license_api.name')
        ) ?>
    </h1>
    <?php if ( is_array( $errors ) && count( $errors ) > 0 ) : ?>
        <div class="notices">
            <?php foreach ( $errors as $key => $messages ) : ?>
                <div class="notice notice-error <?= $key ?>">
                    <ul>
                        <?php foreach ( $messages as $message ) : ?>
                            <li><?= $message ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            <?php endforeach ?>
        </div>
    <?php endif ?>
    <?php if ( !empty( $response ) && isset( $response->message ) ) : ?>
        <div class="notices">
            <div class="notice notice-success">
                <ul>
                    <li><?= $response->message ?></li>
                </ul>
            </div>
        </div>
    <?php endif ?>
    <?php if ( !empty( $response ) && $activated ) : ?>
        <script type="text/javascript">location.reload();</script>
        <?php die ?>
    <?php endif ?>
    <div class="panel">
        <?php if ( $license ) : ?>
            <h2><?php _e( 'License Key Activated', 'wpmvc-addon-license-key' ) ?></h2>
            <table class="short_table">
                <tbody>
                    <?php if ( isset( $license->data->the_key ) ) : ?>
                        <tr>
                            <th><?php _e( 'License Key Code', 'wpmvc-addon-license-key' ) ?></th>
                            <td><code class="the-key"><?= $license->data->the_key ?></code></td>
                        </tr>
                    <?php endif ?>
                    <?php if ( isset( $license->data->activation_id ) ) : ?>
                        <tr>
                            <th><?php _e( 'Activation ID', 'wpmvc-addon-license-key' ) ?></th>
                            <td>
                                <?= $license->data->activation_id ?>
                                <?php if ( $license->data->activation_id === 404 ) : ?>
                                    <span> <?php _e( '(development activation)', 'wpmvc-addon-license-key' ) ?></span>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    <?php if ( isset( $license->data->activation_id ) && $license->data->activation_id !== 404 ) : ?>
                        <tr>
                            <th><?php _e( 'Activation date', 'wpmvc-addon-license-key' ) ?></th>
                            <td><?= date( get_option( 'date_format' ), $license->data->activation_id ) ?></td>
                        </tr>
                    <?php endif ?>
                    <?php if ( isset( $license->data->expire ) && $license->data->expire ) : ?>
                        <tr>
                            <th><?php _e( 'Expires', 'wpmvc-addon-license-key' ) ?></th>
                            <td><?= date( get_option( 'date_format' ), $license->data->expire ) ?></td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <th><?php _e( 'Status', 'wpmvc-addon-license-key' ) ?></th>
                        <td>
                            <?php if ( $main->is_valid ) : ?>
                                <span class="status-valid"><?php _e( 'Valid activation.', 'wpmvc-addon-license-key' ) ?></span>
                            <?php else : ?>
                                <span class="status-invalid"><?php _e( 'Activation no longer valid.', 'wpmvc-addon-license-key' ) ?></span>
                            <?php endif ?>
                        </td>
                    </tr>
                    <?php if ( isset( $license->data->errors ) && ! empty( $license->data->errors ) ) : ?>
                        <tr>
                            <th><?php _e( 'Errors', 'wpmvc-addon-license-key' ) ?></th>
                            <td>
                                <?php foreach ( $license->data->errors as $key => $errors ) : ?>
                                    <dl class="response-errors">
                                        <dt><?= esc_attr( $key ) ?></dt>
                                        <dd>
                                            <?php if ( is_array( $errors ) ) : ?>
                                                <ul class="errors">
                                                    <?php foreach ( $errors as $message ) :?>
                                                        <li><?php _e( $message, 'wpmvc-addon-license-key' ) ?></li>
                                                    <?php endforeach ?>
                                                </ul>
                                            <?php else : ?>
                                                <?= $errors ?>
                                            <?php endif ?>
                                        </dd>
                                    </dl>
                                <?php endforeach ?>
                            </td>
                        </tr>
                    <?php endif ?>
                </tbody>
            </table>
            <div class="actions">
                <form method="POST">
                    <button class="button remove"
                        type="submit"
                        name="action"
                        value="deactivate"
                    >
                        <?php _e( 'Deactivate', 'wpmvc-addon-license-key' ) ?>
                    </button>
                    <button class="button button-primary"
                        type="submit"
                        name="action"
                        value="validate"
                    >
                        <?php _e( 'Validate', 'wpmvc-addon-license-key' ) ?>
                    </button>
                </form>
            </div>
        <?php else : ?>
            <h2><?php _e( 'Activate your License Key', 'wpmvc-addon-license-key' ) ?></h2>
            <form method="POST">
                <table class="short_table">
                    <tbody>
                        <tr>
                            <th><?php _e( 'License Key Code', 'wpmvc-addon-license-key' ) ?></th>
                            <td>
                                <input name="license_key"
                                    class="input"
                                    type="text"
                                    placeholder="<?php _e( 'XXXXXXXXXXXXXXXXXXXXXXXXX-XXX', 'wpmvc-addon-license-key' ) ?>"
                                    value="<?= $license_key ?>"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="actions">
                    <button class="button button-primary"
                        type="submit"
                        name="action"
                        value="activate"
                    >
                        <?php _e( 'Activate', 'wpmvc-addon-license-key' ) ?>
                    </button>
                </div>
            </form>
        <?php endif ?>
    </div><!--.panel-->
    <?php do_action( 'addon_license_key_after_manage_page_' . $ref ) ?>
</div><!--wrap-->