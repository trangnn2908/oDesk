// JavaScript Document

var Utils = {};

Utils.getQueryStrings = function() {
    var assoc = {};
    var decode = function(s) {
        return decodeURIComponent(s.replace(/\+/g, " "));
    };
    var queryString = location.search.substring(1);
    var keyValues = queryString.split('&');

    for (var i in keyValues) {
        var key = keyValues[i].split('=');
        if (key.length > 1) {
            assoc[decode(key[0])] = decode(key[1]);
        }
    }

    return assoc;
};

$(function() {

    /*$('.image-title button').on('click', function(e) {
     $(this).addClass("selected");
     $('.image-title button:eq(0)').text(Utils.data.nnumber);
     $('.image-title button:eq(1)').text(Utils.data.pnumber);
     });*/

    var urlParams = Utils.getQueryStrings();

    $.ajax({
        url: 'curl.php',
        type: 'GET',
        dataType: 'json',
        data: {id: urlParams['id'], access_token: urlParams['access_token'], format: 'json'},
        success: function(data, stat, xhr) {
            if (data !== null) {
                Utils.data = data;
                $('.compare-image:eq(0)').attr('src', data.image1);
                $('.compare-image:eq(1)').attr('src', data.image2);
                $('.compare-title:eq(0)').text(data.compareitemleft);
                $('.compare-title:eq(1)').text(data.compareitemright);
                $('.owner-name').text(data.ownername);
                $('.owner-icon img').attr({src: data.ownerimage, alt: data.ownername, title: data.ownername});
                $('.post-desc').html(data.content);

                $('.image-title button:eq(0)').text(data.nnumber + (((data.nnumber > 1) || (data.number == 0)) ? " tilts" : " tilt"));
                $('.image-title button:eq(1)').text(data.pnumber + (((data.pnumber > 1) || (data.pnumber == 0)) ? " tilts" : " tilt"));

                var postDate = new Date(parseInt(data.date));
                
                var monthWord = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                $('.post-date').text("posted on " + monthWord[postDate.getMonth() - 1] + " " + postDate.getDate() + ", " + postDate.getFullYear());


                $('.image-divider').css({height: $('.compare-image-container').height()});

                /* Change meta data for facebook share */
                $('meta[property=og\\:image]').attr('content', document.location.origin + "/" + data.merged_image);
                $('meta[property=og\\:title]').attr('content', data.ownername + "'s post");
                $('meta[property=og\\:description]').attr('content', data.content);
                $('meta[property=og\\:url]').attr('content', document.location.href);
            }
        }
    });

});