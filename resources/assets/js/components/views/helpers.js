export default {
    methods: {
         string_to_slug (str) {


            str = str.replace(/^\s+|\s+$/g, ''); // trim
            str = str.toLowerCase();
          
            // remove accents, swap ñ for n, etc
            var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
            var to   = "aaaaeeeeiiiioooouuuunc------";
            for (var i=0, l=from.length ; i<l ; i++) {
                str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
            }

            str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                .replace(/\s+/g, '-') // collapse whitespace and replace by -
                .replace(/-+/g, '-'); // collapse dashes

            return str;
        },
        formatImagesGallery(str,searchStr,replaceStr){
           if(!!str){
                searchStr = searchStr.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                
                return str.replace(new RegExp(searchStr, 'gi'), replaceStr);
           }
                
               

        },
        formatesetresImages(id,image){
            return "https://s3.amazonaws.com/meetworks/thumbnails/"+id+"/"+image
        },
        formatGallerySite(image,id){
            return "https://s3.amazonaws.com/meetworks/thumbnails/"+id+"/"+image;
            
            
        },
        fotmatProfileLogo(image,id){
            var image=this.formatImagesGallery(image,"full","medium");
            return "https://s3.amazonaws.com/meetworks/thumbnails/"+id+"/logo/"+image
        },
        fotmatProfileLogoMap(image,id){
            var image=this.formatImagesGallery(image,"full","small");
            return "https://s3.amazonaws.com/meetworks/thumbnails/"+id+"/logo/"+image
        },
        formatImagesLogo(str,size){
               
                return str;

        },
        isMobile(){
          var isMobile = {
                Android: function() {
                    return navigator.userAgent.match(/Android/i);
                },
                BlackBerry: function() {
                    return navigator.userAgent.match(/BlackBerry/i);
                },
                iOS: function() {
                    return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                },
                Opera: function() {
                    return navigator.userAgent.match(/Opera Mini/i);
                },
                Windows: function() {
                    return navigator.userAgent.match(/IEMobile/i) || navigator.userAgent.match(/WPDesktop/i);
                },
                any: function() {
                    return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                }
            };  
            
            return isMobile;
        },
        formatImagesSingleGallery(str){
            str=str.split(".");
            var extension=str[1];
            var imgeString=str[0];
            console.log(imgeString+"-image(300x300-crop-grayscale)."+str[1]);
            return imgeString+"-image(300x300-crop-grayscale)."+str[1];

        }


    },
};