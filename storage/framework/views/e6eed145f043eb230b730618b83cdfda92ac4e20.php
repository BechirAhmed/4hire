<?php echo Form::open(['url' => '', 'class' =>'wt-formtheme wt-userform wt-stripe-form', 'id' =>'stripe-form', '@submit.prevent'=>'submitStripeSettings']); ?>

    <div class="wt-location wt-tabsinfo">
        <div class="wt-tabscontenttitle">
            <h2><?php echo e(trans('lang.stripe_settings')); ?></h2>
        </div>
        <div class="wt-settingscontent">
            <div class="wt-formtheme wt-userform">
                <div class="form-group">
                    <?php echo Form::text('stripe_key', e($stripe_key), ['class' => 'form-control', 'placeholder' => trans('lang.stripe_key')]); ?>

                </div>
            </div>
        </div>
        <div class="wt-settingscontent">
            <div class="wt-formtheme wt-userform">
                <div class="form-group">
                    <?php echo Form::text('stripe_secret', e($stripe_secret), ['class' => 'form-control', 'placeholder' => trans('lang.stripe_secret')]); ?>

                </div>
            </div>
        </div>
    </div>
    <div class="wt-updatall la-updateall-holder">
        <i class="ti-announcement"></i>
        <span><?php echo e(trans('lang.save_changes_note')); ?></span>
        <?php echo Form::submit(trans('lang.btn_save'),['class' => 'wt-btn']); ?>

    </div>
    
<?php echo Form::close(); ?>

