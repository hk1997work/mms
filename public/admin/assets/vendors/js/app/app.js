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
				$('.navbar-header .brand-small').hide();
				$('.navbar-header .brand-big').show();
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
    // 页脚(未使用)
    // ------------------------------------------------------ //
	$(window).resize(function(){
	    var height = $(this).height() - $(".header").height() + $(".main-footer").height()
	    $('.d-scroll').height(height);
	})

	$(window).resize();

    // ------------------------------------------------------- //
    // 滚动条高度适应(未使用)
    // ------------------------------------------------------ //
	$(window).resize(function() {
		$('.auto-scroll').height($(window).height() - 130);
	});

	$(window).trigger('resize');

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
    // 勾选(自定义)
    // ------------------------------------------------------ //
	$('.checkbox').click(function(){
        $(this).toggleClass('is-checked');
    });

    // ------------------------------------------------------- //
    // 全部勾选submit
    // ------------------------------------------------------ //
    $("#check-all").change(function () {
        $("input:checkbox").prop('checked', $(this).prop("checked"));
    });

    // ------------------------------------------------------- //
    // Card Close(未使用)
    // ------------------------------------------------------ //
    $('a.remove').on('click', function (e) {
        e.preventDefault();
        $(this).parents('.col-remove').fadeOut(500);
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
	// 右侧边栏滚动条(未使用)
	// ------------------------------------------------------ //
	$(".offcanvas-scroll").niceScroll({
		railpadding: {
			top: 0,
			right: 2,
			left: 0,
			bottom: 0
		},
		scrollspeed: 100,
		zindex: "auto",
		hidecursordelay: 800,
		cursorwidth: "3px",
		cursorcolor: "rgba(52, 40, 104, 0.1)",
		cursorborder: "rgba(52, 40, 104, 0.1)",
		preservenativescrolling: true,
		boxzoom: false
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

	// ------------------------------------------------------- //
	// 右侧边栏(未使用)
	// ------------------------------------------------------ //
	$(function () {
		//open
		$('.open-sidebar').on('click', function (event) {
			event.preventDefault();
			$('.off-sidebar').addClass('is-visible');
		});
		//close
		$('.off-sidebar').on('click', function (event) {
			if ($(event.target).is('.off-sidebar') || $(event.target).is('.off-sidebar-close')) {
				$('.off-sidebar').removeClass('is-visible');
				event.preventDefault();
			}
		});
	});
})(jQuery);
