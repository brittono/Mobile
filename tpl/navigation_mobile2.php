<div class="navigation2">
	<form id="new_post_form">
		<label></label><textarea tabindex= "10" name="new_post_title" id="new_post_title" placeholder="Content to post" ></textarea>
		<a href="javascript:" class="canvas_button" data-dnt="true" data-count="none" data-via="">2PNG</a>
		<div id="new_post_gravatar"></div>
		<a href="javascript:" class='mobile_3d_button'><div id="mobile_3D"><span style="color: blue;">3</span><span style="color: red;">D</span></div></a>
		<!-- <input tabindex= "11" name="new_post_content" id="new_post_content" type="textarea" placeholder="Initial Article Content (optional)"/> -->
		<input tabindex= "12" id="new_post_button" type="button" value="Post It"/>
        <input tabindex= "13" id="clear_canvas" type="button" value="Clear"/>
	</form>
    
    <div class="sliders">
    
        <div class="slider_container">
           X: <div id="rotateX"></div>
        </div>
        
        <div class="slider_container">
           Y: <div id="rotateY"></div>
        </div>
        
        <div class="slider_container">
           Z: <div id="rotateZ"></div>
        </div>
        
        <div class="slider_container">
           P: <div id="perspective"></div>
        </div>
        
        <div class="slider_container">
           O: <div id="origin"></div>
        </div>
    
    </div>    
    
	<div class="post_error"></div>
</div>

<script>

	$("#mobile_container").on("touchmove", false);

	$('#image_container').css({
		perspective: '230px'
	});

	function rotateX( off ) {
	
	   $('.image_container').css( 'transform', 'rotateX('+off+'deg)' );
	}
	
	function rotateY( off ) {
	
	
	    var $box, cnt;
	
	    $box = $('#image_preview > div');
	    cnt = $box.length;
	
	    $box.each(function (i, e)
		{
			$(e).css( 'transform', 'rotateY('+Math.round( ( 360/cnt*i + off ) % 360 )+'deg)' );
		});
		
		$('.image_container').css( 'transform', 'rotateY('+off+'deg)' );
	}
	
	function rotateZ( off ) {
	
	   $('.image_container').css( 'transform', 'rotateZ('+off+'deg)' );
	}

	$('#rotateX').slider({ min: 0, max: 720,
		value: 25,
		slide: function ( e, ui ) {
			rotateX( ui.value );
		}
	});

	$('#rotateY').slider({ min: 0, max: 720,
        value: 10,
    	slide: function ( e, ui ) {
    		rotateY( ui.value );
    	}
    });

    $('#rotateZ').slider({ min: 0, max: 720,
        value: 25,
    	slide: function ( e, ui ) {
    		rotateZ( ui.value );
    	}
    });

	$('#perspective').slider({ min: 200, max: 4000,
        value: 800,
    	slide: function ( e, ui ) {
			$('#image_container').css({
				perspective: ui.value + 'px'
			});
    	}
    });

	$('#origin').slider({ min: 0, max: 1000,
        value: 100,
    	slide: function ( e, ui ) {
			$('#image_container').css({
				transformOrigin: '50% 50% -'+ ui.value +'px'
			});
    	}
    });

</script>