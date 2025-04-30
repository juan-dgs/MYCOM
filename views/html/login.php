<?php
include(HTML.'master/head.php');
include(HTML.'master/topnav.php');
?>
	<link rel="stylesheet" type="text/css" href="views/css/Login24/vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="views/css/Login24/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="views/css/Login24/css/util.css">
	<link rel="stylesheet" type="text/css" href="views/css/Login24/css/main.css">



  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/easing/EasePack.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/latest/TweenLite.min.js"></script>


  <script src="views/js/gologin.js?v=1" charset="utf-8"></script>


<style>

::-webkit-scrollbar {
display: none;
}

.login100-form-avatar {
    width: 100%;
     height: 100%; 
    border-radius: 0%; 
    overflow: hidden;
    margin: 0 auto;
}
.container-login100::before {
        content: "";
        display: block;
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        background: #005bea;
        background: -webkit-linear-gradient(bottom,rgb(161 215 255), rgb(0 0 0) );
        background: -o-linear-gradient(bottom,rgb(161 215 255), rgb(0 0 0));
        background: -moz-linear-gradient(bottom,rgb(161 215 255), rgb(0 0 0));
        background: linear-gradient(bottom,rgb(161 215 255), rgb(0 0 0));
        opacity: 0.8;
    }

.login100-form-btn::before {
    content: "";
    display: block;
    position: absolute;
    z-index: -1;
    width: 100%;
    height: 100%;
    border-radius: 25px;
    top: 0;
    left: 0;
    background: #005bea;
    background: -webkit-linear-gradient(left, #dc3545, #820000);
    background: -o-linear-gradient(left, #005bea, #00c6fb);
    background: -moz-linear-gradient(left, #005bea, #00c6fb);
    background: linear-gradient(left, #005bea, #00c6fb);
    -webkit-transition: all 0.4s;
    -o-transition: all 0.4s;
    -moz-transition: all 0.4s;
    transition: all 0.4s;
    opacity: 0;
}

.novisible {
    display:none;
}

.containerX{
    position: absolute;
    z-index: 100;
    RIGHT: 0;
    TOP: 0;
}

.wrap-input100,.container-login100-form-btn {
    z-index: 1000;
}

.modoX {
    margin: 0;
    line-height: 0;
    text-align: right;
    margin-top: -50px;
    font-size: 40px;
}

.login100-form-btn {
        background: #2e8fd6;
}

    .login100-form-btn::before {
           background: -webkit-linear-gradient(left,#2e8fd6 ,rgb(26, 83, 124));
    }

    .p-b-10 {
        padding-bottom: 80px;
    }

#Password {
 -webkit-text-security: disc !important;
}

#UserName,#Password {
    text-transform: uppercase;
}
</style>


<div class="limiter">
	<div class="container-login100" id="fondo-login" style="    transition: all 0.5s ease 0s;  background:#000; background-image: url('views/images/web/banners/banner1.jpg'); background-size:cover; ">
		<div class="wrap-login100  p-b-10">
            <div class="containerX demo-1">
                <div id="large-header" class="large-header">
                    <canvas id="demo-canvas"></canvas>
                </div>
            </div>
           

			<div id='login-form' class="login100-form validate-form" >
				<div class="login100-form-avatar">
					<img src="views\images\web\logos\mycom.png" alt="MyCom">
				</div>

                <span class="login100-form-title p-t-20 p-b-45 modoX">
                </span>

                <div class="wrap-input100 validate-input m-b-10">
                    <input class="input100" type="text" id="UserName" placeholder="Usuario" autocomplete="off" spellcheck="false" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-user"></i>
                    </span>
                </div>

                <div class="wrap-input100 validate-input m-b-10">
                    <input class="input100" type="text" id="Password" placeholder="Contraseña" autocomplete="off" spellcheck="false" />
                    <span class="focus-input100"></span>
                    <span class="symbol-input100">
                        <i class="fa fa-lock"></i>
                    </span>
                </div>


                <div class="container-login100-form-btn p-t-10">
					<button class="login100-form-btn" type="button" onclick="GoLogin();">Entrar</button>
				</div>
                

                <div class="container-login100-form-btn p-t-10" id="divCargando"></div>
			</div>
           
		</div>
         
</div>
</div>

    
    

    <script>
      (function () {
    var width, height, largeHeader, canvas, ctx, points, target, animateHeader = true;
    // Main
    initHeader();
    initAnimation();
    addListeners();

    function initHeader() {
        width = window.innerWidth;
        height = window.innerHeight;
        target = { x: width / 2, y: height / 2 };

        largeHeader = document.getElementById('large-header');
        largeHeader.style.height = height + 'px';

        canvas = document.getElementById('demo-canvas');
        canvas.width = width;
        canvas.height = height;
        ctx = canvas.getContext('2d');

        // create points
        points = [];
        for (var x = 0; x < width; x = x + width / 10) {
            for (var y = 0; y < height; y = y + height / 20) {
                var px = x + Math.random() * width / 10;
                var py = y + Math.random() * height / 10;
                var p = { x: px, originX: px, y: py, originY: py };
                points.push(p);
            }
        }

        // for each point find the 5 closest points
        for (var i = 0; i < points.length; i++) {
            var closest = [];
            var p1 = points[i];
            for (var j = 0; j < points.length; j++) {
                var p2 = points[j]
                if (!(p1 == p2)) {
                    var placed = false;
                    for (var k = 0; k < 3; k++) {
                        if (!placed) {
                            if (closest[k] == undefined) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }

                    for (var k = 0; k < 3; k++) {
                        if (!placed) {
                            if (getDistance(p1, p2) < getDistance(p1, closest[k])) {
                                closest[k] = p2;
                                placed = true;
                            }
                        }
                    }
                }
            }
            p1.closest = closest;
        }

        // assign a circle to each point
        for (var i in points) {
            var c = new Circle(points[i], 2 + Math.random() * 2, 'rgba(255, 255, 255, 0.89)'); //background: rgb(121 184 45 / 67%);
            points[i].circle = c;
        }
    }

    // Event handling
    function addListeners() {
        if (!('ontouchstart' in window)) {
            window.addEventListener('mousemove', mouseMove);
        }
        window.addEventListener('scroll', scrollCheck);
        window.addEventListener('resize', resize);
    }

    function mouseMove(e) {
        var posx = posy = 0;
        if (e.pageX || e.pageY) {
            posx = e.pageX;
            posy = e.pageY;
        }
        else if (e.clientX || e.clientY) {
            posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
            posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }
        target.x = posx;
        target.y = posy;
    }

    function scrollCheck() {
        if (document.body.scrollTop > height) animateHeader = false;
        else animateHeader = true;
    }

    function resize() {
        width = window.innerWidth;
        height = window.innerHeight;
        largeHeader.style.height = height + 'px';
        canvas.width = width;
        canvas.height = height;
    }

    // animation
    function initAnimation() {
        animate();
        for (var i in points) {
            shiftPoint(points[i]);
        }
    }

    function animate() {
        if (animateHeader) {
            ctx.clearRect(0, 0, width, height);
            for (var i in points) {
                // detect points in range
                if (Math.abs(getDistance(target, points[i])) < 4000) {
                    points[i].active = 0.3;
                    points[i].circle.active = 0.6;
                } else if (Math.abs(getDistance(target, points[i])) < 20000) {
                    points[i].active = 0.1;
                    points[i].circle.active = 0.3;
                } else if (Math.abs(getDistance(target, points[i])) < 40000) {
                    points[i].active = 0.02;
                    points[i].circle.active = 0.1;
                } else {
                    points[i].active = 0;
                    points[i].circle.active = 0;
                }

                drawLines(points[i]);
                points[i].circle.draw();
            }
        }
        requestAnimationFrame(animate);
    }

    function shiftPoint(p) {
        TweenLite.to(p, 1 + 1 * Math.random(), {
            x: p.originX - 50 + Math.random() * 100,
            y: p.originY - 50 + Math.random() * 100, ease: Circ.easeInOut,
            onComplete: function () {
                shiftPoint(p);
            }
        });
    }

    // Canvas manipulation
    function drawLines(p) {
        if (!p.active) return;
        for (var i in p.closest) {
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.closest[i].x, p.closest[i].y);
            ctx.strokeStyle = 'rgba(255,255,255,' + p.active + ')';
            ctx.stroke();
        }
    }

    function Circle(pos, rad, color) {
        var _this = this;

        // constructor
        (function () {
            _this.pos = pos || null;
            _this.radius = rad || null;
            _this.color = color || null;
        })();

        this.draw = function () {
            if (!_this.active) return;
            ctx.beginPath();
            ctx.arc(_this.pos.x, _this.pos.y, _this.radius, 0, 4 * Math.PI, false);
            ctx.fillStyle = 'rgba(255,255,255,' + _this.active + ')';
            ctx.fill();
        };
    }

    // Util
    function getDistance(p1, p2) {
        return Math.pow(p1.x - p2.x, 2) + Math.pow(p1.y - p2.y, 2);
    }

})();

(function () {
    var lastTime = 0;
    var vendors = ['ms', 'moz', 'webkit', 'o'];
    for (var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
        window.requestAnimationFrame = window[vendors[x] + 'RequestAnimationFrame'];
        window.cancelAnimationFrame = window[vendors[x] + 'CancelAnimationFrame']
            || window[vendors[x] + 'CancelRequestAnimationFrame'];
    }

    if (!window.requestAnimationFrame)
        window.requestAnimationFrame = function (callback, element) {
            var currTime = new Date().getTime();
            var timeToCall = Math.max(0, 16 - (currTime - lastTime));
            var id = window.setTimeout(function () { callback(currTime + timeToCall); },
                timeToCall);
            lastTime = currTime + timeToCall;
            return id;
        };

    if (!window.cancelAnimationFrame)
        window.cancelAnimationFrame = function (id) {
            clearTimeout(id);
        };
}());

function launchFullScreen(element) {
    console.log("PANTALLA COMPLETA...");
    if (element.requestFullScreen) {
        element.requestFullScreen();
    } else if (element.mozRequestFullScreen) {
        element.mozRequestFullScreen();
    } else if (element.webkitRequestFullScreen) {
        element.webkitRequestFullScreen();
    }
}

function cancelFullScreen() {
    if (document.cancelFullScreen) {
        document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
        document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
        document.webkitCancelFullScreen();
    }
}
    </script>

    <?php include(HTML.'master/foot.php'); ?>
