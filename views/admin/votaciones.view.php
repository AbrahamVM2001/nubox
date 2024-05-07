<link href="<?= constant('URL') ?>public/css/style.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenMax.min.js"></script>
<?php require('views/estilos.view.php'); ?>
<div class="col-sm-12 col-md-12 col-lg-6 col-xl-6">
    <div class="wrap">
        <div class="countdown">
            <div class="bloc-time sec" data-init-value="20">
                <div class="figure sec sec-1">
                    <span class="top">2</span>
                    <span class="top-back">
                        <span>2</span>
                    </span>
                    <span class="bottom">2</span>
                    <span class="bottom-back">
                        <span>2</span>
                    </span>
                </div>
                <div class="figure sec sec-2">
                    <span class="top">0</span>
                    <span class="top-back">
                        <span>0</span>
                    </span>
                    <span class="bottom">0</span>
                    <span class="bottom-back">
                        <span>0</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="panel-votacion" id="panel-votacion" style="background-color: #f8f9fa !important;">
</div>
<?php require('views/footer.view.php'); ?>
<script>
    let id_pregunta = '<?= $this->pregunta; ?>';
    var Countdown = {

        $el: $('.countdown'),

        // Params
        countdown_interval: null,
        total_seconds: 20, // Cambiamos aquí el total de segundos

        init: function() {

            // DOM
            this.$ = {
                hours: this.$el.find('.bloc-time.hours .figure'),
                minutes: this.$el.find('.bloc-time.min .figure'),
                seconds: this.$el.find('.bloc-time.sec .figure')
            };

            this.count();
        },

        count: function() {

            var that = this,
                $hour_1 = this.$.hours.eq(0),
                $hour_2 = this.$.hours.eq(1),
                $min_1 = this.$.minutes.eq(0),
                $min_2 = this.$.minutes.eq(1),
                $sec_1 = this.$.seconds.eq(0),
                $sec_2 = this.$.seconds.eq(1);

            this.countdown_interval = setInterval(function() {

                if (that.total_seconds > 0) {

                    --that.total_seconds;

                    var hours = Math.floor(that.total_seconds / 3600);
                    var minutes = Math.floor((that.total_seconds % 3600) / 60);
                    var seconds = that.total_seconds % 60;

                    that.checkHour(hours, $hour_1, $hour_2);
                    that.checkHour(minutes, $min_1, $min_2);
                    that.checkHour(seconds, $sec_1, $sec_2);
                } else {
                    clearInterval(that.countdown_interval);
                }
            }, 1000);
        },

        animateFigure: function($el, value) {

            var that = this,
                $top = $el.find('.top'),
                $bottom = $el.find('.bottom'),
                $back_top = $el.find('.top-back'),
                $back_bottom = $el.find('.bottom-back');

            $back_top.find('span').html(value);
            $back_bottom.find('span').html(value);

            TweenMax.to($top, 0.8, {
                rotationX: '-180deg',
                transformPerspective: 300,
                ease: Quart.easeOut,
                onComplete: function() {
                    $top.html(value);
                    $bottom.html(value);
                    TweenMax.set($top, {
                        rotationX: 0
                    });
                }
            });

            TweenMax.to($back_top, 0.8, {
                rotationX: 0,
                transformPerspective: 300,
                ease: Quart.easeOut,
                clearProps: 'all'
            });
        },

        checkHour: function(value, $el_1, $el_2) {

            var val_1 = Math.floor(value / 10);
            var val_2 = value % 10;
            var fig_1_value = $el_1.find('.top').html();
            var fig_2_value = $el_2.find('.top').html();

            if (value >= 10) {
                if (fig_1_value !== val_1.toString()) this.animateFigure($el_1, val_1);
                if (fig_2_value !== val_2.toString()) this.animateFigure($el_2, val_2);
            } else {
                if (fig_1_value !== '0') this.animateFigure($el_1, 0);
                if (fig_2_value !== val_1.toString()) this.animateFigure($el_2, val_1);
            }
        }
    };

    Countdown.init();
</script>
<script src="<?= constant('URL') ?>public/js/paginas/admin/home.votacion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>