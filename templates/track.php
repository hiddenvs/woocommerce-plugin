<?php get_header(); ?>
<div ng-app="shopping-cart-demo">
  <div ng-controller="AltcoinController">
    <div class="bnomics-order-container">
      <!-- Heading row -->
      <div class="bnomics-order-heading">
        <div class="bnomics-order-heading-wrapper">
          <div class="bnomics-order-id">
            <span class="bnomics-order-number" ng-cloak><?= __('Order #', 'blockonomics-bitcoin-payments') ?>{{order.order_id}}</span>
          </div>
        </div>
      </div>
      <!-- Spinner -->
      <div class="bnomics-spinner" ng-show="spinner" ng-cloak><div class="bnomics-ring"><div></div><div></div><div></div><div></div></div></div>
      <!-- Amount row -->
      <div class="bnomics-order-panel">
        <div class="bnomics-order-info" ng-init="altcoin_waiting=true">
          <div class="bnomics-bitcoin-pane" ng-hide="show_altcoin != 0" ng-init="show_altcoin=1"></div>
          <div class="bnomics-altcoin-pane" ng-style="{'border-left': (altcoin_waiting)?'none':''}" ng-hide="show_altcoin != 1">
            <div class="bnomics-altcoin-waiting" ng-show="altcoin_waiting" ng-init="altcoin_waiting=true" ng-cloak>
              <!-- WAITING_FOR_DEPOSIT -->
              <div class="bnomics-btc-info" style="display: flex;flex-wrap: wrap;" ng-show="order.altstatus == 'waiting'" ng-cloak>
                <div style="flex: 1">
                  <!-- QR Code -->
                  <div class="bnomics-qr-code">
                    <div class="bnomics-qr">
                      <a href="{{altcoinselect}}:{{order.altaddress}}?amount={{order.altamount}}&value={{order.altamount}}">
                        <qrcode data="{{altcoinselect}}:{{order.altaddress}}?amount={{order.altamount}}&value={{order.altamount}}" size="160" version="6">
                          <canvas class="qrcode"></canvas>
                        </qrcode>
                      </a>
                    </div>
                    <div class="bnomics-qr-code-hint">
                      <?= __('Click on the QR code to open in the wallet', 'blockonomics-bitcoin-payments') ?>
                    </div>
                  </div>
                </div>
                <div style="flex: 2;">
                  <div class="bnomics-altcoin-bg-color">
                    <!-- Payment Text -->
                    <div class="bnomics-order-status-wrapper">
                      <span class="bnomics-order-status-title" ng-show="order.altstatus == 'waiting'" ng-cloak >
                        <?= __('To confirm your order, please send the exact amount of <strong>{{altcoinselect}}</strong> to the given address', 'blockonomics-bitcoin-payments') ?>
                      </span>
                    </div>
                    <h4 class="bnomics-amount-title" for="invoice-amount">
                      {{order.altamount}} {{order.altsymbol}}
                    </h4>
                    <!-- Altcoin Address -->
                    <div class="bnomics-address">
                      <input ng-click="alt_address_click()" id="bnomics-alt-address-input" class="bnomics-address-input" type="text" ng-value="order.altaddress" readonly="readonly">
                      <i ng-click="alt_address_click()" class="material-icons bnomics-copy-icon">file_copy</i>
                    </div>
                    <div class="bnomics-copy-text" ng-show="copyshow" ng-cloak>
                      <?= __('Copied to clipboard', 'blockonomics-bitcoin-payments') ?>
                    </div>
                    <!-- Countdown Timer -->
                    <div ng-cloak ng-hide="order.altstatus != 'waiting'  || alt_clock <= 0" class="bnomics-progress-bar-wrapper">
                      <div class="bnomics-progress-bar-container">
                        <div class="bnomics-progress-bar" style="width: {{alt_progress}}%;">
                        </div>
                      </div>
                    </div>
                    <span class="ng-cloak bnomics-time-left" ng-hide="order.altstatus != 'waiting' || alt_clock <= 0">{{alt_clock*1000 | date:'mm:ss' : 'UTC'}} min left to pay your order
                    </span>
                  </div>
                  <div class="bnomics-altcoin-cancel">
                    <a href="" ng-click="go_back()"><?= __('Click here', 'blockonomics-bitcoin-payments') ?></a> <?= __('to go back', 'blockonomics-bitcoin-payments') ?>
                  </div>
                  <!-- Blockonomics Credit -->
                  <div class="bnomics-powered-by">
                    <?= __('Powered by ', 'blockonomics-bitcoin-payments') ?>Blockonomics
                  </div>
                </div>
              </div>
              <!-- RECEIVED -->
              <div class="bnomics-altcoin-bg-color" ng-show="order.altstatus == 'received'" ng-cloak>
                <h4>Received</h4>
                <h4><i class="material-icons bnomics-alt-icon">check_circle</i></h4>
                <?= __('Your payment has been received and your order will be processed shortly.', 'blockonomics-bitcoin-payments') ?>
              </div>
              <!-- ADD_REFUND -->
              <div class="bnomics-status-flex bnomics-altcoin-bg-color" ng-show="order.altstatus == 'add_refund'" ng-cloak >
                <h4>Refund Required</h4>
                <p><?= __('Your order couldn\'t be processed as you didn\'t pay the exact expected amount.<br>The amount you paid will be refunded.', 'blockonomics-bitcoin-payments') ?></p>
                <h4><i class="material-icons bnomics-alt-icon">error</i></h4>
                <p><?= __('Enter your refund address and click the button below to recieve your refund.', 'blockonomics-bitcoin-payments') ?></p>
                <input type="text" id="bnomics-refund-input" placeholder="{{order.altsymbol}} Address">
                <br>
                <button id="alt-refund-button" ng-click="add_refund_click()">Refund</button>
              </div>
              <!-- REFUNDED no txid-->
              <div class="bnomics-status-flex bnomics-altcoin-bg-color" ng-show="order.altstatus == 'refunded'" ng-cloak >
                <h4>Refund Submitted</h4>
                <p><?= __('Your refund details have been submitted. You should recieve your refund shortly.', 'blockonomics-bitcoin-payments') ?></p>
                <h4><i class="material-icons bnomics-alt-icon">autorenew</i></h4>
                <p><?= __('If you don\'t get refunded in a few hours, contact <a href="mailto:hello@flyp.me">hello@flyp.me</a> with the following uuid:', 'blockonomics-bitcoin-payments') ?><br><span id="alt-uuid">{{altuuid}}</span></p>
              </div>
              <!-- REFUNDED with txid-->
              <div class="bnomics-status-flex bnomics-altcoin-bg-color" ng-show="order.altstatus == 'refunded-txid'" ng-cloak >
                <h4>Refunded</h4>
                <h4><i class="material-icons bnomics-alt-icon">autorenew</i></h4>
                <p><?= __('This payment has been refunded.', 'blockonomics-bitcoin-payments') ?></p>
                <div>
                  <?= __('Refund Details:', 'blockonomics-bitcoin-payments') ?>
                  <div class="bnomics-small bnomics-bold bnomics-left"><?= __('Transaction ID:', 'blockonomics-bitcoin-payments') ?></div> 
                  <div class="bnomics-small bnomics-left" id="alt-refund-txid">{{order.alttxid}}</div>
                  <div class="bnomics-small bnomics-bold bnomics-left"><?= __('Transaction URL:', 'blockonomics-bitcoin-payments') ?></div>
                  <div class="bnomics-small bnomics-left" id="alt-refund-url"><a href="{{order.alturl}}" target="_blank">{{order.alturl}}</a></div>
                </div>
              </div>
              <!-- EXPIRED -->
              <div class="bnomics-status-flex bnomics-altcoin-bg-color" ng-show="order.altstatus == 'expired'" ng-cloak >
                <h4>Expired</h4>
                <h4><i class="material-icons bnomics-alt-icon">timer</i></h4>
                <p><?= __('Payment Expired. Use the browser back button and try again.', 'blockonomics-bitcoin-payments') ?></p>
              </div>
              <!-- LOW/HIGH -->
              <div class="bnomics-status-flex bnomics-altcoin-bg-color" ng-show="order.altstatus == 'low_high'" ng-cloak >
                <h4>Error</h4>
                <h4><i class="material-icons bnomics-alt-icon">error</i></h4>
                <p><?= __('Order amount too <strong>{{lowhigh}}</strong> for {{order.altsymbol}} payment.', 'blockonomics-bitcoin-payments') ?></p>
                <p><a href="" ng-click="go_back()"><?= __('Click here', 'blockonomics-bitcoin-payments') ?></a> <?= __('to go back and use BTC to complete the payment.', 'blockonomics-bitcoin-payments') ?></p>
              </div>
            </div>
          <!-- Blockonomics Credit -->
          <div class="bnomics-powered-by" ng-hide="order.altstatus == 'waiting'"><?= __('Powered by ', 'blockonomics-bitcoin-payments') ?>Blockonomics</div>
        </div>
      </div>
    </div>
  </div>
  <script>
    var blockonomics_time_period=<?php echo get_option('blockonomics_timeperiod', 10); ?>;
  </script>
  <script>
    var get_uuid="<?php if (isset($_REQUEST['uuid'])) { echo $_REQUEST['uuid']; } ?>";
  </script>
</div>
<?php get_footer(); ?>