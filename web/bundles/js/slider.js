jQuery(document).ready(function ($) {
    var options = {
        $AutoPlay: 1,
        $AutoPlaySteps: 1,
        $Idle: 3000,
        $PauseOnHover: 1,
        $ArrowKeyNavigation: 1,
        $SlideEasing: $Jease$.$OutQuint,
        $SlideDuration: 1250,
        $MinDragOffsetToSlide: 20,
        $SlideSpacing: 0,
        $Cols: 1,
        $Align: 0,
        $SlideWidth: 1300,
        $SlideHeight: 650,
        $UISearchMode: 1,
        $PlayOrientation: 1,
        $DragOrientation: 1,

        $ArrowNavigatorOptions: {
            $Class: $JssorArrowNavigator$,
            $ChanceToShow: 2,
            $Steps: 1
        },

        $BulletNavigatorOptions: {
            $Class: $JssorBulletNavigator$,
            $ChanceToShow: 2,
            $Steps: 1,
            $Rows: 1,
            $SpacingX: 12,
            $Orientation: 1
        }
    };

    var jssor_slider1 = new $JssorSlider$("slider1_container", options);
    function ScaleSlider() {
        var parentWidth = jssor_slider1.$Elmt.parentNode.clientWidth;
        if (parentWidth) {
            jssor_slider1.$ScaleWidth(parentWidth + 20);
        }
        else
            window.setTimeout(ScaleSlider, 30);
    }
    ScaleSlider();

    $(window).bind("load", ScaleSlider);
    $(window).bind("resize", ScaleSlider);
    $(window).bind("orientationchange", ScaleSlider);
});