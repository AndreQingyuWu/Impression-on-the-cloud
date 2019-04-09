/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

// window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app'
// });

jQuery(function ($) {
    // init the state from the input
    $(".image-checkbox").each(function () {
        if ($(this).find('input[type="checkbox"]').first().attr("checked")) {
            $(this).addClass('image-checkbox-checked');
        }
        else {
            $(this).removeClass('image-checkbox-checked');
        }
    });

    // sync the state to the input
    $(".image-checkbox").on("click", function (e) {
        if ($(this).hasClass('image-checkbox-checked')) {
            $(this).removeClass('image-checkbox-checked');
            $(this).find('input[type="checkbox"]').first().removeAttr("checked");
        }
        else {
            $(this).addClass('image-checkbox-checked');
            $(this).find('input[type="checkbox"]').first().attr("checked", "checked");
        }

        e.preventDefault();
    });

    $("#imageFile").on('change', function () {

        if (typeof (FileReader) != "undefined") {

            var preview = $("#preview");

            var reader = new FileReader();

            reader.onload = function (e) {
                preview.attr('src', e.target.result)
            }
            reader.readAsDataURL($(this)[0].files[0]);

            start2('preview');
        } else {
            alert("你的浏览器不支持FileReader.");
        }
    });

    // 上传图片事件
    $("#uploadImageButton").on('click', function () {
        if (typeof(window.characteristic_value) == "undefined" || typeof(window.topClassesAndProbs) == "undefined") {
            alert('图片矩阵正在计算，请稍等几秒..');
            return;
        }
        let url = $("#uploadImage").attr("action");
        var form = new FormData(document.getElementById("uploadImage"));

        $.each(window.topClassesAndProbs, function (i, e) {
            form.append("keys[]", e.className);
        })
        form.append("characteristicValue", window.characteristic_value);
        $.ajax({
            url: url,
            type: "post",
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.status == "success") {
                    alert("上传成功");
                }
            },
            error: function (e) {
                alert("请选择要上传的网盘并填写名称");
            }
        });
    });

    // 图片搜图
    $("#searchImageButton1").on('click', function () {
        if (typeof(window.characteristic_value) == "undefined" || typeof(window.topClassesAndProbs) == "undefined") {
            alert('图片矩阵正在计算，请稍等几秒..');
            return;
        }

        var form = new FormData();

        let url = $("#searchImage1").attr("action");
        let csrf_token = $("#csrf_token").val();
        form.append("characteristicValue", window.characteristic_value);
        form.append("_token", csrf_token);
        let clouds = [];
        $(".image-checkbox-checked input[name='clouds[]']").each(function (i) {
            clouds[i] = $(this).val();
        });
        form.append("clouds", clouds);
        $.ajax({
            url: url,
            type: "post",
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.length === 0){
                    alert('没有搜索到图片')
                } else{
                    alert('搜索成功')
                }
                $("#imageResult").empty();
                $.each(data, function (i, e) {
                    dom = `
                        <div class="col-md-3">
            <div class="card mt-4">
            <div class="card-header">
                            ${e.source}
                        </div>
                <img class="card-img-top"
                     src="/storage/${e.path}">
                <div class="card-body">
                    <p class="card-text">${e.name}</p>
                </div>
                <div class="card-footer text-muted">
                    ${e.sim * 100}%
                </div>
            </div>
        </div>
                    `;
                    $("#imageResult").append(dom);
                })
            },
            error: function (e) {
                alert("识图失败");
            }
        });
    });

    // 文字搜索
    $("#searchImageButton2").on('click', function () {
        var form = new FormData();
        let url = $("#searchImage2").attr("action");
        let csrf_token = $("#csrf_token").val();
        let name = $("#name").val();
        let clouds = [];
        $(".image-checkbox-checked input[name='clouds[]']").each(function (i) {
            clouds[i] = $(this).val();
        });
        form.append("_token", csrf_token);
        form.append("name", name);
        form.append("clouds", clouds);
        $.ajax({
            url: url,
            type: "post",
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.length === 0){
                    alert('没有搜索到图片')
                } else{
                    alert('搜索成功')
                }
                $("#imageResult").empty();
                $.each(data, function (i, e) {
                    dom = `
                        <div class="col-md-3">
            <div class="card mt-4">
                <div class="card-header">
                            ${e.source}
                        </div>
                <img class="card-img-top"
                     src="/storage/${e.path}">
                <div class="card-body">
                    <p class="card-text">${e.name}</p>
                </div>
            </div>
        </div>
                    `;
                    $("#imageResult").append(dom);
                })
            },
            error: function (e) {
                alert("错误！！");
            }
        });
    });

    // 手绘搜索
    $("#searchImageButton3").on('click', function () {
        if (typeof(window.topClassesAndProbs) == "undefined") {
            alert('图片矩阵正在计算，请稍等几秒..');
            return;
        }
        var form = new FormData();
        let url = $("#searchImage3").attr("action");
        let csrf_token = $("#csrf_token").val();
        $.each(window.topClassesAndProbs, function (i, e) {
            form.append("keys[]", e.className);
        })
        form.append("_token", csrf_token);
        let clouds = [];
        $(".image-checkbox-checked input[name='clouds[]']").each(function (i) {
            clouds[i] = $(this).val();
        });
        form.append("clouds", clouds);
        $.ajax({
            url: url,
            type: "post",
            data: form,
            processData: false,
            contentType: false,
            success: function (data) {
                if (data.length === 0){
                    alert('没有搜索到图片')
                } else{
                    alert('搜索成功')
                }
                $("#imageResult").empty();
                $.each(data, function (i, e) {
                    dom = `
                        <div class="col-md-3">
            <div class="card mt-4">
            <div class="card-header">
                            ${e.source}
                        </div>
                <img class="card-img-top"
                     src="/storage/${e.path}">
                <div class="card-body">
                    <p class="card-text">${e.name}</p>
                </div>
                <div class="card-footer text-muted">
                    ${e.sim * 100}%
                </div>
            </div>
        </div>
                    `;
                    $("#imageResult").append(dom);
                })
            },
            error: function (e) {
                alert("错误！！");
            }
        });
    });
});

var canvas = document.getElementById("canvas");
var cxt = canvas.getContext("2d");
cxt.fillStyle ="#fff";
cxt.fillRect(0, 0, 900, 600);
var color = document.getElementById("color");
var size = document.getElementById("range");
var demo = document.getElementById("demo");
//根据size的变化来使得size上面的线条演示画笔粗细。
size.onchange = function () {
    demo.style.height = size.value + "px";
}
//使得color的颜色与演示线条的颜色一致
color.onchange = function () {
    demo.style.background = color.value;
}
var flag = false;
//鼠标按下
canvas.onmousedown = function (e) {
    var mouseX = e.pageX - this.offsetLeft;
    var mouseY = e.pageY - this.offsetTop;
    flag = true;
    cxt.beginPath();
    cxt.lineWidth = size.value;
    cxt.strokeStyle = color.value;
    cxt.moveTo(mouseX, mouseY);
};
//鼠标移动
canvas.onmousemove = function (e) {
    var mouseX = e.pageX - this.offsetLeft;
    var mouseY = e.pageY - this.offsetTop;
    if (flag) {

        cxt.lineTo(mouseX, mouseY);
        cxt.stroke();
    }
}
//鼠标松开
canvas.onmouseup = function (e) {
    flag = false;
    start1('canvas');
}