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

                
                searchStr = searchStr.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
                
                return str.replace(new RegExp(searchStr, 'gi'), replaceStr);

        },
        formatImagesLogo(str,size){
                str=str.toLowerCase();
                
                str=str.replace(new RegExp("uploads", 'gi'), "thumbs");
                str=str.replace(new RegExp("logo", 'gi'), "logo-"+size);
                return str;

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