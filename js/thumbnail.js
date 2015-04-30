/* 自動サムネイル表示プラグイン for jQuery
2009.1 By KaZuhiro FuRuhata  */
$.fn.thumbnail = function(baseURL){
    var serverURL = "http://capture.heartrails.com/100x100/round/border/shadow?";
    var index = 0;
    return this.each(function(){
        /*$(this).hover(
            function(e){
                var url = $(this).attr("href");
                $("#jqThumbnail").attr("src",serverURL+url);
                $("#jqThumbnail").css("left",e.pageX+10);
                $("#jqThumbnail").css("top",e.pageY+10);
                $("#jqThumbnail").show();
            },
            function(){
                $("#jqThumbnail").hide();
            }
        );*/
        var cook_url = $(this).attr("href");
        $(this).append("<img src='' id='jqThumbnail' width='100' height='100'>");
        $.ajax({
            url:cook_url,
            dataType:'html',
            crossDomain: true,
            type:"GET",
            element:this,
            success: function(data) {
                    //console.log(data);
                    //console.log($(data.responseText).find("#main-photo img.large_photo_clickable"));
                    console.log($(data.responseText).find("#main-photo img.large_photo_clickable").attr("src"));
                    var img_url = $(data.responseText).find("#main-photo img.large_photo_clickable").attr("src");
                    $(this.element).find('img').attr("src", img_url);
            },
        });

        //$("#jqThumbnail").attr("src",serverURL+url);
        //$("#jqThumbnail").css("left",e.pageX+10);
        //$("#jqThumbnail").css("top",e.pageY+10);
        //$("#jqThumbnail").show();
    });
}
$(function(){
    //$("body").append("<img src='' id='jqThumbnail' width='100' height='100' style='position:absolute;display:none'>");
    $("a.thumnail").thumbnail();
});
