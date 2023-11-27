(function ($) {

	'use strict';

    // ------------------------------------------------------- //
    // 加载动画
    // ------------------------------------------------------ //
	$(window).on("load", function () {
		$(".loader").fadeOut();
		$("#preloader").fadeOut("slow");
	});

	// ------------------------------------------------------- //
	// 侧边栏功能
	// ------------------------------------------------------ //
	$('#toggle-btn').on('click', function (e) {
		e.preventDefault();
		$(this).toggleClass('active');

		$('.side-navbar').toggleClass('shrinked');
		$('.content-inner').toggleClass('active');

		if ($(window).outerWidth() > 1183) {
			if ($('#toggle-btn').hasClass('active')) {
                $('.navbar-header .brand-big').show();
				$('.navbar-header .brand-small').hide();
			} else {
				$('.navbar-header .brand-small').show();
				$('.navbar-header .brand-big').hide();
			}
		}

		if ($(window).outerWidth() < 1183) {
			$('.navbar-header .brand-small').show();
		}
	});
	// Close dropdown after click
	$(function () {
	    $(".side-navbar li a").click(function(event) {
		    $(".collapse").collapse('hide');
	    });
	});

    // ------------------------------------------------------- //
    // 回到顶部
    // ------------------------------------------------------ //
    $(function () {
        // Show or hide the sticky footer button
        $(window).scroll(function () {
            if ($(this).scrollTop() > 350) {
                $('.go-top').fadeIn(100);
            } else {
                $('.go-top').fadeOut(200);
            }
        });

        // Animate the scroll to top
        $('.go-top').click(function (event) {
            event.preventDefault();

            $('html, body').animate({
                scrollTop: 0
            }, 800);
        })
    });

    // ------------------------------------------------------- //
    // 全部勾选submit
    // ------------------------------------------------------ //
    $("#check-all").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

	// ------------------------------------------------------- //
	// 侧边栏滚动条
	// ------------------------------------------------------ //
	$(".sidebar-scroll").niceScroll({
		cursorcolor: "transparent",
		cursorborder: "transparent",
		cursoropacitymax: 0,
		boxzoom: false,
		autohidemode: "hidden",
		cursorfixedheight: 80
	});

	// ------------------------------------------------------- //
	// 小部件滚动条
	// ------------------------------------------------------ //
	$(".widget-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 3,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		autohidemode: "leave",
		cursorwidth: "4px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)"
	});

	// ------------------------------------------------------- //
	// 表格滚动条
	// ------------------------------------------------------ //
	$(".table-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 0,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		autohidemode: "leave",
		cursorwidth: "4px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)"
	});

	// ------------------------------------------------------- //
	// 下拉菜单滑动效果
	// ------------------------------------------------------ //
    $('.dropdown').on('show.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    $('.dropdown').on('hide.bs.dropdown', function(e){
      $(this).find('.dropdown-menu').first().stop(true, true).slideUp(300);
    });

	// ------------------------------------------------------- //
	// 下拉菜单悬停效果
	// ------------------------------------------------------ //
	$('.widget-options > .dropdown, .actions > .dropdown, .quick-actions > .dropdown').hover(function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(350);
	}, function () {
		$(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(350);
	});
})(jQuery);
