/*单图初始化*/
function initAvatarPicker(picker, successCallBack) {
    var iconUploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,
        swf: 'webupload/Uploader.swf',
        server: MAIN_URL+'/admin/File/uploadImg',
        pick: picker,
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        method:'POST',
        duplicate :true
    });

    iconUploader.on('uploadSuccess', function(file, response) {
        if(response.code == 0){
            if (successCallBack) {
                successCallBack(response);
            }
        } else {
            alert(response.message);
        }
    });
}

function uploadImg(imgClass) {
    /**
     * Created by agantongxue on 2017/8/7.
     */
    /*init webuploader*/
    var $list=$("#thelist");   //这几个初始化全局的百度文档上没说明，好蛋疼。
//    var $btn =;   //开始上传
    var filecount = 0;

    var imgbox = $('.imgBox');
//      var thumbnailWidth = 100;   //缩略图高度和宽度 （单位是像素），当宽高度是0~1的时候，是按照百分比计算，具体可以看api文档
//      var thumbnailHeight = 100;


    $("#ctlBtn").on('click',function () {
        // 上传图片
        uploader.upload();
    });

    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: 'webupload/Uploader.swf',

        // 文件接收服务端。
        server: '/admin/File/uploadImg',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        method:'POST',
        duplicate :true,
    });

// 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {  // webuploader事件.当选择文件后，文件被加载到文件队列中，触发该事件。等效于 uploader.onFileueued = function(file) ，类似js的事件定义。

        filecount++;
        var $li = $(
                '<div id="' + file.id + '" class="img">'+
                '<img>'+
                '<span id="remove' + file.id + '" class="webuploadDelbtn glyphicon glyphicon-remove" aria-hidden="true"></span>'+
                '</div>'
            ),
            $img = $li.find('img');

        // $list为容器jQuery实例
        imgbox.append( $li );
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {   //webuploader方法

            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        },1, 1 );
        // 点击删除按钮
        $('#remove' + file.id).on('click',function () {
            uploader.removeFile(file);
            console.log('删除');
        });
    });


// 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
            $percent = $li.find('.progress .progress-bar');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress"> <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%"> <span class="sr-only"></span> </div> </div>')
                .appendTo( $li )
                .find('.progress-bar');
        }

        $percent.css( 'width', percentage * 100 + '%' );

        $percent.text(  percentage.toFixed(2) * 100 + '%' );

    });


// 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ,response) {
        $( '#'+file.id ).addClass('upload-state-done');

        console.log(imgClass);

        if (response.code == 0)
        {
            $('.coverImgInput').val(response.data);
            $('#camp_coverimg').attr('src',response.data);

            var text = '<input id="input_'+file.id+'" type="hidden" name="'+imgClass+'[]" class="'+imgClass+'" value="'+ response.data +'">'
            $('.imgBox').append(text);
        } else {
            alert(response.message);
        }

    });

// 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        var $li = $( '#'+file.id ),
            $error = $li.find('div.error');

        // 避免重复创建
        if ( !$error.length ) {
            $error = $('<div class="error"></div>').appendTo( $li );
        }

        $error.text('上传图片失败');
    });


    uploader.onFileDequeued = function( file ) {

        filecount--;
        console.log('将文件移除队列');
        removeFile(file);
    };


// 负责view的销毁
    function removeFile( file ) {

        console.log('销毁view');

        var $li = $('#'+file.id);

        $li.remove();
        if ($('#input_'+file.id).length)
        {
            $('#input_'+file.id).remove();
        }
    }

    uploader.on( 'uploadFinished', function( file ) {

        // //停止菊花
        // setTimeout(function(){
        //     layer.closeAll('loading');
        // }, 2000);

        //alert('上传图片成功!');

    });

// 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
//          $( '#'+file.id ).find('.progress').remove();
    });
}

// 删除已有照片
function delImg(img_id) {
    var text = '<input type="hidden" name="del_img_ids[]" value="'+img_id+'">';
    $('#img_'+img_id).remove();
    $('#delImgs').append(text);
}



function uploadImg_en(imgClass) {
    /**
     * Created by agantongxue on 2017/8/7.
     */
    /*init webuploader*/
    var $list=$("#thelist_en");   //这几个初始化全局的百度文档上没说明，好蛋疼。
//    var $btn =;   //开始上传
    var filecount_en = 0;

    var imgbox = $('.imgBox_en');
//      var thumbnailWidth = 100;   //缩略图高度和宽度 （单位是像素），当宽高度是0~1的时候，是按照百分比计算，具体可以看api文档
//      var thumbnailHeight = 100;


    $("#ctlBtn").on('click',function () {
        // 上传图片
        uploader.upload();
    });

    var uploader = WebUploader.create({
        // 选完文件后，是否自动上传。
        auto: true,

        // swf文件路径
        swf: 'webupload/Uploader.swf',

        // 文件接收服务端。
        server: MAIN_URL+'/admin/File/uploadImg',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker_en',

        // 只允许选择图片文件。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        method:'POST',
        duplicate :true,
    });

// 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {  // webuploader事件.当选择文件后，文件被加载到文件队列中，触发该事件。等效于 uploader.onFileueued = function(file) ，类似js的事件定义。

        filecount_en++;
        var $li = $(
            '<div id="' + file.id + '" class="img">'+
            '<img>'+
            '<span id="remove' + file.id + '" class="webuploadDelbtn glyphicon glyphicon-remove" aria-hidden="true"></span>'+
            '</div>'
            ),
            $img = $li.find('img');

        // $list为容器jQuery实例
        imgbox.append( $li );
        // 创建缩略图
        // 如果为非图片文件，可以不用调用此方法。
        // thumbnailWidth x thumbnailHeight 为 100 x 100
        uploader.makeThumb( file, function( error, src ) {   //webuploader方法

            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        },1, 1 );
        // 点击删除按钮
        $('#remove' + file.id).on('click',function () {
            uploader.removeFile(file);
            console.log('删除');
        });
    });


// 文件上传过程中创建进度条实时显示。
    uploader.on( 'uploadProgress', function( file, percentage ) {
        var $li = $( '#'+file.id ),
            $percent = $li.find('.progress .progress-bar');

        // 避免重复创建
        if ( !$percent.length ) {
            $percent = $('<div class="progress"> <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%"> <span class="sr-only"></span> </div> </div>')
                .appendTo( $li )
                .find('.progress-bar');
        }

        $percent.css( 'width', percentage * 100 + '%' );

        $percent.text(  percentage.toFixed(2) * 100 + '%' );

    });


// 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ,response) {
        $( '#'+file.id ).addClass('upload-state-done');

        console.log(imgClass);

        if (response.code == 0)
        {

            var text = '<input id="input_'+file.id+'" type="hidden" name="'+imgClass+'[]" class="'+imgClass+'" value="'+ response.data +'">'
            $('.imgBox_en').append(text);
        } else {
            alert(response.message);
        }

    });

// 文件上传失败，显示上传出错。
    uploader.on( 'uploadError', function( file ) {
        var $li = $( '#'+file.id ),
            $error = $li.find('div.error');

        // 避免重复创建
        if ( !$error.length ) {
            $error = $('<div class="error"></div>').appendTo( $li );
        }

        $error.text('上传图片失败');
    });


    uploader.onFileDequeued = function( file ) {

        filecount_en--;
        console.log('将文件移除队列');
        removeFile(file);
    };


// 负责view的销毁
    function removeFile( file ) {

        console.log('销毁view');

        var $li = $('#'+file.id);

        $li.remove();
        if ($('#input_'+file.id).length)
        {
            $('#input_'+file.id).remove();
        }
    }

    uploader.on( 'uploadFinished', function( file ) {

        // //停止菊花
        // setTimeout(function(){
        //     layer.closeAll('loading');
        // }, 2000);

        //alert('上传图片成功!');

    });

// 完成上传完了，成功或者失败，先删除进度条。
    uploader.on( 'uploadComplete', function( file ) {
//          $( '#'+file.id ).find('.progress').remove();
    });
}

// 删除已有照片
function delImg_en(img_id) {
    var text = '<input type="hidden" name="del_img_ids_en[]" value="'+img_id+'">';
    $('#img_'+img_id).remove();
    $('#delImgs_en').append(text);
}