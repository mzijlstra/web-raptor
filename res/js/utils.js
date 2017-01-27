/* 
 Created on : Jul 07, 2015
 Author     : mzijlstra
 */

var $__popupCount = 0;
var $__gfxWindows = [];

(function () {
    // private variable to count graphics windows

    // private helper function
    var toRad = function (deg) {
        return deg / 180 * Math.PI;
    };

    // functions related to the turtle itself
    var drawTurtle = function () {
        var ctx = this.display[0].getContext("2d");
        ctx.clearRect(0, 0, 32, 32);

        // draw body
        ctx.fillStyle = "#882";
        var path = new Path2D();
        path.moveTo(26, 17);
        path.arc(16, 17, 10, 0, Math.PI * 2);
        ctx.fill(path);
        ctx.stroke(path);

        // draw limbs
        ctx.fillStyle = "#4A4";
        path = new Path2D();
        path.moveTo(16, 5);
        path.arc(16, 5, 4, 0, Math.PI * 2);
        path.moveTo(8, 12);
        path.arc(8, 12, 4, 0, Math.PI * 2);
        path.moveTo(24, 12);
        path.arc(24, 12, 4, 0, Math.PI * 2);
        path.moveTo(11, 25);
        path.arc(11, 25, 4, 0, Math.PI * 2);
        path.moveTo(21, 25);
        path.arc(21, 25, 4, 0, Math.PI * 2);
        ctx.fill(path);

        // draw face
        ctx.strokeStyle = "#4A4";
        path = new Path2D();
        path.moveTo(14, 1);
        path.lineTo(18, 1);
        ctx.stroke(path);
        ctx.strokeStyle = "purple";
        ctx.lineWidth = 2;
        path = new Path2D();
        path.moveTo(12, 2);
        path.lineTo(20, 2);
        path.moveTo(20, 4);
        path.lineTo(22, 4);
        ctx.stroke(path);
        ctx.lineWidth = 1;
        ctx.strokeStyle = "black";
        path = new Path2D();
        path.moveTo(13, 2.5);
        path.lineTo(15, 2.5);
        path.moveTo(17, 2.5);
        path.lineTo(19, 2.5);
        path.moveTo(13, 5.5);
        path.lineTo(19, 5.5);
        path.moveTo(16, 5.5);
        path.lineTo(16, 6.5);
        path.moveTo(13, 5.5);
        path.lineTo(13, 4.5);
        path.moveTo(19, 5.5);
        path.lineTo(19, 4.5);
        ctx.stroke(path);
    };
    var drawPenDown = function () {
        var ctx = this.display[0].getContext("2d");
        var path = new Path2D();
        ctx.fillStyle = "black";
        path.moveTo(30, 28);
        path.lineTo(28, 30);
        path.lineTo(9, 6);
        path.lineTo(11, 4);
        ctx.fill(path);
        path = new Path2D();
        path.moveTo(30, 30);
        path.lineTo(28, 30);
        path.lineTo(30, 28);
        ctx.fillStyle = this.penIsColor;
        ctx.fill(path);
    };
    var drawPenUp = function () {
        var ctx = this.display[0].getContext("2d");
        ctx.strokeStyle = "rgb(0,0,0,0.5)";
        ctx.lineWidth = 1;
        var path = new Path2D();
        path.moveTo(28, 30);
        path.lineTo(32, 30);
        path.moveTo(30, 28);
        path.lineTo(30, 32);
        ctx.stroke(path);
        ctx.fillStyle = "black";
        path = new Path2D();
        path.moveTo(0, 12);
        path.lineTo(32, 12);
        path.lineTo(32, 15);
        path.lineTo(0, 15);
        ctx.fill(path);
    };

    var toString = function () {
        return "[object Turtle]";
    };
    var forward = function (amount) {
        var dx = Math.cos(toRad(this.deg)) * amount;
        var dy = Math.sin(toRad(this.deg)) * amount;
        this.display.css({
            "top": this.y + dy - 30,
            "left": this.x + dx - 30
        });
        if (this.penIsDown) {
            var path = new Path2D();
            path.moveTo(this.x, this.y);
            path.lineTo(this.x + dx, this.y + dy);
            this.parentCtx.stroke(path);
        }
        this.x += dx;
        this.y += dy;
        return this;
    };
    var rotate = function (degree) {
        this.deg += degree;
        this.deg = this.deg % 360;
        this.display.css({
            "transform": "rotate(" + this.deg + "deg)"
        });
        return this;
    };
    var rotateRight = function (degree) {
        rotate(-degree);
    };
    var penUp = function () {
        this.penIsDown = false;
        this.drawTurtle();
        this.drawPenUp();
        return this;
    };
    var penDown = function () {
        this.penIsDown = true;
        this.drawTurtle();
        this.drawPenDown();
        return this;
    };
    var penColor = function (color) {
        this.penIscolor = color;
        this.drawTurtle();
        if (this.penIsDown) {
            this.drawPenDown();
        } else {
            this.drawPenUp();
        }
        this.parentCtx.strokeStyle = color;
        return this;
    };
    var penWidth = function (width) {
        this.lineWidth = width;
        this.parentCtx.lineWidth = width;
        return this;
    };
    var show = function () {
        this.display.show();
        return this;
    };
    var hide = function () {
        this.display.hide();
        return this;
    };
    var moveTo = function (nx, ny) {
        this.display.css({
            "top": ny - 16,
            "left": nx - 16
        });
        if (this.penIsDown) {
            var path = new Path2D();
            path.moveTo(this.x, this.y);
            path.lineTo(nx, ny);
            this.parentCtx.stroke(path);
        }
        this.x = nx;
        this.y = ny;
        return this;
    };
    var rotateTo = function (degree) {
        this.deg = degree % 360;
        this.display.css({
            "transform": "rotate(" + (this.deg + 90) + "deg)"
        });
        return this;
    };

    // private function to create a turtle on a graphics window
    var createTurtle = function () {
        // Create a turtle display
        var display = $("<canvas width='32' height='32'>");
        display.css({
            "position": "absolute",
            "top": $(this.document).height() / 2 - 30,
            "left": $(this.document).width() / 2 - 30,
            "transform-origin": "30px 30px"
        });
        $(this.document.body).append(display);

        var turtle = {
            // variables
            "display": display,
            "parentCtx": $(this.document).find("#canvas")[0].getContext('2d'),
            "deg": 0,
            "penIsColor": "black",
            "penIsDown": true,
            "lineWidth": 2,
            "x": $(this.document).width() / 2,
            "y": $(this.document).height() / 2,
            // system functions
            "drawTurtle": drawTurtle,
            "drawPenUp": drawPenUp,
            "drawPenDown": drawPenDown,
            "toString": toString,
            // user functions
            "forward": forward,
            "rotate": rotate,
            "left": rotate,
            "right": rotateRight,
            "penUp": penUp,
            "penDown": penDown,
            "penColor": penColor,
            "penWidth": penWidth,
            "show": show,
            "hide": hide,
            "moveTo": moveTo,
            "rotateTo": rotateTo
        };

        turtle.parentCtx.lineWidth = turtle.lineWidth;
        turtle.parentCtx.strokeStyle = turtle.penIsColor;

        turtle.drawTurtle();
        turtle.drawPenDown();

        return turtle;
    };

    var loadImage = function (file) {
        var canvas = $(this.document).find("#canvas")[0];
        return new Promise(function (resolve, reject) {
            var img = new Image();
            img.src = "res/img/" + file;
            var ctx = canvas.getContext('2d');
            img.onload = function () {
                ctx.drawImage(img, 0, 0);
                img.style.display = 'none';
                resolve(img);
            };
            img.onerror = function (e) {
                reject(e);
            };
        });
    };

    var getRedAt = function (x, y) {
        var w = this.width;
        var d = this.data;
        return d[(4 * y * w) + (4 * x) + 0];
    };
    var getGreenAt = function (x, y) {
        var w = this.width;
        var d = this.data;
        return d[(4 * y * w) + (4 * x) + 1];
    };
    var getBlueAt = function (x, y) {
        var w = this.width;
        var d = this.data;
        return d[(4 * y * w) + (4 * x) + 2];
    };
    var getAlphaAt = function (x, y) {
        var w = this.width;
        var d = this.data;
        return d[(4 * y * w) + (4 * x) + 3];
    };
    var setRedAt = function (x, y, val) {
        var w = this.width;
        var d = this.data;
        d[(4 * y * w) + (4 * x) + 0] = val;
        return this;
    };
    var setGreenAt = function (x, y, val) {
        var w = this.width;
        var d = this.data;
        d[(4 * y * w) + (4 * x) + 1] = val;
        return this;
    };
    var setBlueAt = function (x, y, val) {
        var w = this.width;
        var d = this.data;
        d[(4 * y * w) + (4 * x) + 2] = val;
        return this;
    };
    var setAlphaAt = function (x, y, val) {
        var w = this.width;
        var d = this.data;
        d[(4 * y * w) + (4 * x) + 3] = val;
        return this;
    };

    var getImageData = function () {
        var canvas = $(this.document).find("#canvas")[0];
        var ctx = canvas.getContext('2d');
        var data = ctx.getImageData(0, 0, canvas.width, canvas.height);
        data.getRedAt = getRedAt;
        data.getGreenAt = getGreenAt;
        data.getBlueAt = getBlueAt;
        data.getAplphaAt = getAlphaAt;
        data.setRedAt = setRedAt;
        data.setGreenAt = setGreenAt;
        data.setBlueAt = setBlueAt;
        data.setAlphaAt = setAlphaAt;
        return data;
    };

    var putImageData = function (imgData, x, y) {
        if (!x) {
            x = 0;
        }
        if (!y) {
            y = 0;
        }
        var canvas = $(this.document).find("#canvas")[0];
        var ctx = canvas.getContext('2d');
        ctx.putImageData(imgData, x, y);
    };

    var getPixels = function () {
        var win = this;
        var data = getImageData.call(this);

        var getRed = function () {
            return data.getRedAt(this.x, this.y);
        };
        var getGreen = function () {
            return data.getGreenAt(this.x, this.y);
        };
        var getBlue = function () {
            return data.getBlueAt(this.x, this.y);
        };
        var getAlpha = function () {
            return data.getAlphaAt(this.x, this.y);
        };
        var setRed = function (val) {
            data.setRedAt(this.x, this.y, val);
            return this;
        };
        var setGreen = function (val) {
            data.setGreenAt(this.x, this.y, val);
            return this;
        };
        var setBlue = function (val) {
            data.setBlueAt(this.x, this.y, val);
            return this;
        };
        var setAlpha = function (val) {
            data.setAlphaAt(this.x, this.y, val);
            return this;
        };
        var createPixel = function (x, y) {
            return {
                "x": x,
                "y": y,
                "getRed": getRed,
                "getGreen": getGreen,
                "getBlue": getBlue,
                "getAlpha": getAlpha,
                "setRed": setRed,
                "setGreen": setGreen,
                "setBlue": setBlue,
                "setAlpha": setAlpha
            };
        };
        var pixels = {};
        var count = 0;
        for (var y = 0; y < data.height; y++) {
            for (var x = 0; x < data.width; x++) {
                pixels[count] = (createPixel(x, y));
                count++;
            }
        }
        pixels.length = count;
        pixels.show = function() {
            putImageData.call(win, data);
        };
        pixels.toString = function () {
            return "[array PixelData]";
        };
        return pixels;
    };

    /**
     * public function to create a graphics window
     * 
     * @param {int} width
     * @param {int} height
     * @param {string} color
     * @returns {object|Window.TGWindow.win|Window|utils_L7.createCanvasPopup.win|window.TGWindow.win}
     */
    window.GfxWindow = function (width, height, color) {
        // parameter defaults (if not provided)
        if (!width || typeof (width) !== 'number') {
            width = 400;
        }
        if (!height || typeof (height) !== 'number') {
            height = 300;
        }
        if (!color) {
            color = "#FFF";
        }

        // we may be inside an iframe
        var w = window;
        if (window.top) {
            w = window.top;
        }

        // create the window object
        var parent_x = $(document).width();
        var features = "top=250,left=" + (parent_x - width) + ",width=" +
                (width + 1) + ",height=" + (height + 4) + ",menubar=0";

        var win = window.open(undefined, "popup" + $__popupCount, features);
        if (!win) {
            throw "Unable to create window -- is the popup blocked?";
        }
        $__gfxWindows[$__popupCount] = win;

        $__popupCount++;
        win.document.title = "Graphics Window " + $__popupCount;

        // create the window content
        var body = $(win.document.body);
        body.css({"margin": "0"});
        var canvas = $("<canvas id='canvas' width='" + width + "' height='" +
                height + "'>");
        body.append(canvas);
        var ctx = $(body).find("#canvas")[0].getContext("2d");
        ctx.fillStyle = color;
        ctx.fillRect(0, 0, width, height);


        // add the window public methods
        win.createTurtle = createTurtle;
        win.loadImage = loadImage;
        win.getImageData = getImageData;
        win.putImageData = putImageData;
        win.getPixels = getPixels;
        win.toString = function () {
            return "[object CanvasWindow]";
        };
        return win;
    };
    window.$__closePopups = function () {
        $__gfxWindows.forEach(function (elem) {
            elem.close();
        });
        $__popupCount = 0;
    };
}());


 