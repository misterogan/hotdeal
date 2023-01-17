<template>
    <div id="view_review_modal" class="modal">
        <div class="modal-dialog w-500">
            <div class="modal-body">
                <span class="close-modal" @click="clearForm"></span>
                <div class="content-modal" v-if="data.products">
                    <div class="review-modal">
                        <h5 class="text-center">berikan ulasan dan komentar untuk pesananmu</h5>
                        <div class="nama-toko">
                            <div class="container-img">
                                <img :src="data.vendor.image ? data.vendor.image : '/img/ic_user.svg'" alt="">
                            </div>
                            <div class="ket-penjual">
                                <h6>penjual : <strong>{{data.vendor.vendor_name}}</strong></h6>
                                <!-- <a class="btn-link">Lihat Toko</a> -->
                            </div>
                        </div>
                        <div class="ulasan" v-for="(item , index) in data.products" :key="index">
                            <div class="title-produk">
                                <h6>{{item.product_info.name}}</h6>
                                <!-- <h6>Test {{data.review[index].review}}</h6> -->
                            </div>
                            <form id="review">
                                <div class="kolom-komentar">
                                    <div class="col-foto">
                                        <div class="container-img">
                                            <img :src="item.product_info.image.link" alt="">
                                        </div>
                                    </div>
                                    <div class="col-ulasan"  v-for="(item1 , index1) in data.review" :key="index1">
                                        <div class="rate">
                                            <ul class="rating">
                                                <li v-for="i in 5" :key="i" @click="changeRating(index , i)"  v-bind:class="{['rating-btn ratings--'+index+'--'+i]: true,'active':i<=item1.rating}" ></li>
                                            </ul>
                                        </div>
                                        <div class="review">
                                            <input type="hidden" v-model="data.review[index].id" :name="'review_id['+index+']'" :key="index">
                                            <textarea  :name="'reviews['+index+']'" :key="index" v-model="data.review[index].review" cols="30" rows="10"></textarea>
                                        </div>
                                        <div class="media-ulasan">
                                            <div class="container-media mright-20">
                                                <h6 class="white-nowrap">foto ulasan </h6>
                                                <div class="upload-media img">
                                                    <img class="icon" src="/img/ic_upload.svg">
                                                    <img class="preview-picture" v-if="item1.image.url_source !=''" :src="item1.image.url_source != '' ? item1.image.url_source : '/img/ic_upload.svg'">
                                                    <input @change="changePreview($event ,index , 'photo')" :name="'image_'+index" type="file" class="add-image" id="img_1">
                                                    <img id="image-review" :class="'preview-picture preview_photo_'+index">
                                                </div>
                                            </div>
                                            <div class="container-media">
                                                <h6 class="white-nowrap">video ulasan</h6>
                                                <div class="upload-media video">
                                                    <img class="icon" src="/img/ic_upload.svg">
                                                    <video class="preview-video" v-if="item1.video.url_source != ''" :src="item1.video.url_source != '' ? item1.video.url_source : '/img/ic_upload.svg'" playsinline autoplay muted loop />       
                                                    <!-- <video class="preview-video" v-if="item1.review_gallery[1].type == 'video'" :src="item1.review_gallery[1].url_source != '' ? item1.review_gallery[1].url_source : '/img/ic_upload.svg'" playsinline autoplay muted loop />       
                                                    <video class="preview-video" v-else :src="item1.review_gallery[0].url_source != '' ? item1.review_gallery[0].url_source : '/img/ic_upload.svg'" playsinline autoplay muted loop />        -->
                                                    <input id="vid_1" @change="changePreview($event ,index , 'video')" :name="'video_'+index" type="file" class="add-image" accept=".jpg,.jpeg.,.gif,.png,.mov,.mp4">      
                                                    <video id="video-review" :class="'preview-video preview_video_'+index" playsinline autoplay muted loop />       
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <button class="btn-primary" @click="updateReview">simpan ulasan</button>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'
    import Message from '../../../utils/Message'
    export default {
        name: "ModalReview.vue",
        props :['data', 'review'],
        data() {
            return {
                form : {
                    pictures : [],
                    ratings : [],
                    videos : [],
                    products : [],
                    reviews : [],
                    inv : ''
                },
                _data : this.data,
                _form : this.data,
                save_button : true,
                source_video : '',
                source_image : '',
            }
        },
        methods :{
            refs(id){
                $( "input[name^='"+id+"']" ).click()
            },
            check_data(){
                let data = this.data.products;
                if(Object.keys(data).length > 0){
                    const product_id = data.reduce((arrayNew, item) => {
                        arrayNew.push(item.product_info.product_id)
                        return arrayNew                    
                    }, []);
                    this.form.products = product_id
                }
            },
            changeRating(index , i){
                this.form.ratings[index] = i
                for(let x=1; x <= 5 ; x++){
                    $('.ratings--'+index+'--'+x).removeClass('active')
                }
                for(let x=1; x <= i ; x++){
                    $('.ratings--'+index+'--'+x).addClass('active')
                }
            },
            updateReview(){
                if(this.save_button == false){
                    return ;
                }
                $("#modal_load").fadeIn();
                this.check_data()
                const fd = new FormData();
                let data = this.form.products
                fd.append('forms[inv]' , this.data.inv)
                for( let i=0; i < data.length; i++){
                    fd.append('forms[data]['+i+'][product]' , data[i])
                    fd.append('forms[data]['+i+'][order_details_id]' , this.data.order_details_id)
                    fd.append('forms[data]['+i+'][picture]' , this.form.pictures[i] ? this.form.pictures[i] : '')
                    fd.append('forms[data]['+i+'][video]' , this.form.videos[i] ? this.form.videos[i] : '')
                    fd.append('forms[data]['+i+'][ratings]' , this.form.ratings[i] ? this.form.ratings[i] : '')
                    fd.append('forms[data]['+i+'][reviews]' , this.data.review[i].review ? this.data.review[i].review : '')
                    fd.append('forms[data]['+i+'][review_id]' , this.data.review[i].id ? this.data.review[i].id : '')
                }
                apiCustomer.updateReviewProduct(fd).then( response => {
                   if(response.data.code == 200){
                    Message.alert('Review berhasil disimpan' , "Informasi" , 1500)
                    window.location.reload()
                   }else{
                       this.save_button = true;
                       Message.alert(response.data.message , "Informasi")
                   }
                   $("#modal_load").fadeOut();
                });
            },
            clearForm(){
                $('.icon-img').removeAttr('src');
                $('#image-review').attr('src', '/img/ic_upload.svg').css('width', '40px').css('height', 'auto');
                $('#video-review').attr('src', '');
                this.form.pictures = []
                this.form.ratings = []
                this.form.products = []
                this.form.reviews = []
                this.form.videos = []
                this.form.inv = ''
            },
            changePreview(event, index , type){
                if(type == 'photo'){
                    if (event) {
                        $('.preview_photo_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        $('#image-review').css('width', '100%').css('height', '100%');
                        this.form.pictures[index] = event.target.files[0] 
                    }
                }else if(type == 'video'){
                    if (event) {
                        $('.preview_video_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        this.form.videos[index] = event.target.files[0] 
                    }
                }
            },
        },
        components:{
            CloseModal,
            Message
        }
    }
</script>

<style>

</style>