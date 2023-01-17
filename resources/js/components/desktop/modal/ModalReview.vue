<template>
    <div>
        <div id="review_modal" class="modal">
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
                            <div class="ulasan" v-for="(item , index) in data.products" :key="'a'+index">
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
                                        <div class="col-ulasan">
                                            <div class="rate">
                                                <ul class="rating">
                                                    <li v-for="i in 5" :key="'b'+i" @click="changeRating(index , i)"  v-bind:class="{['rating-btn ratings--'+index+'--'+i]: true,'active':i==i}" ></li>
                                                </ul>
                                            </div>
                                            <div class="review">
                                                <textarea placeholder="beri ulasan di sini ..." id="review-textarea" :name="'review['+index+']'" v-model="form.reviews[index]" :key="'c'+index" cols="30" rows="10"></textarea>
                                                <!-- <textarea name="" id="" cols="30" rows="10">123</textarea> -->
                                            </div>
                                            <div class="media-ulasan">
                                                <div class="media-upload mright-20">
                                                    <h6 class="white-nowrap">foto ulasan</h6>
                                                    <label for="img_1">
                                                        <div class="upload-icon">
                                                            <img id="image-review" :class="'preview_photo_'+index" src="">
                                                        </div>
                                                    </label>
                                                    <input @change="changePreview($event ,index , 'photo')" :name="'image_'+index" type="file" class="add-image" id="img_1">
                                                </div>
                                                <div class="media-upload mright-20">
                                                    <h6 class="white-nowrap">video ulasan</h6>
                                                    <label for="vid_1">
                                                        <div class="upload-icon">
                                                            <video id="video-review" :class="'preview-video preview_video_'+index" playsinline autoplay muted loop />       
                                                        </div>
                                                    </label>
                                                    <input id="vid_1" @change="changePreview($event ,index , 'video')" :name="'video_'+index" type="file" class="add-image" accept=".jpg,.jpeg.,.gif,.png,.mov,.mp4">      
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <button class="btn-primary" @click="saveReview">kirim ulasan</button>                    
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                            <div class="ulasan" v-for="(item , index) in data.products" :key="'e'+index">
                                <div class="title-produk">
                                    <h6>{{item.product_info.name}}</h6>
                                </div>
                                <form id="review">
                                    <div class="kolom-komentar">
                                        <div class="col-foto">
                                            <div class="container-img">
                                                <img :src="item.product_info.image.link" alt="">
                                            </div>
                                        </div>
                                        <div class="col-ulasan" v-for="(item1 , index1) in data.review" :key="'f'+index1">
                                            <div class="rate">
                                                <ul class="rating">
                                                    <li v-for="i in 5" :key="i" @click="changeRating(index , i)"  v-bind:class="{['rating-btn ratings--'+index+'--'+i]: true,'active':i<=item1.rating}" ></li>
                                                </ul>
                                            </div>
                                            <div class="review">
                                                <input type="hidden" v-model="data.review[index].id" :name="'review_id['+index+']'" :key="'g'+index">
                                                <textarea  :name="'reviews['+index+']'" :key="'h'+index" v-model="data.review[index].review" cols="30" rows="10"></textarea>
                                            </div>
                                            <div class="media-ulasan">
                                                <div class="media-upload mright-20">
                                                    <h6 class="white-nowrap">foto ulasan </h6>
                                                    <label for="img_1">
                                                        <div class="upload-icon">
                                                            <img v-if="item1.image.url_source !=''" id="image-review" :class="'preview_photo_'+index" :src="item1.image.url_source != '' ? item1.image.url_source : ''"></img>
                                                        </div>
                                                    </label>
                                                    <input @change="changePreview($event ,index , 'photo')" :name="'image_'+index" type="file" class="add-image" id="img_1">
                                                </div>
                                                <div class="media-upload">
                                                    <h6 class="white-nowrap">video ulasan</h6>
                                                    <label for="vid_1">
                                                        <div class="upload-icon">
                                                            <video id="video-review" :class="'preview-video preview_video_'+index" v-if="item1.video.url_source != ''" :src="item1.video.url_source != '' ? item1.video.url_source : ''" playsinline autoplay muted loop />       
                                                            <!-- <video id="video-review" :class="'preview-video preview_video_'+index" playsinline autoplay muted loop />        -->
                                                        </div>
                                                    </label>
                                                    <input id="vid_1" @change="changePreview($event ,index , 'video')" :name="'video_'+index" type="file" class="add-image" accept=".jpg,.jpeg.,.gif,.png,.mov,.mp4">      
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
                data_src: '',
                save_button : true
            }
        },
        mounted(){        
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
            changePreview(event, index , type){
                if(type == 'photo'){
                    if (event) {
                        $('.preview_photo_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        this.form.pictures[index] = event.target.files[0] 
                    }
                }else if(type == 'video'){
                    if (event) {
                        $('.preview_video_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        this.form.videos[index] = event.target.files[0] 
                    }
                }
            },
            saveReview(){
                if(this.save_button == false){
                    return ;
                }
                this.check_data()
                const fd = new FormData();
                let data = this.form.products
                fd.append('forms[inv]' , this.data.inv)
                for( let i=0; i < data.length; i++){
                    fd.append('forms[data]['+i+'][product]' , data[i])
                    fd.append('forms[data]['+i+'][order_details_id]' , this.data.order_details_id)
                    fd.append('forms[data]['+i+'][picture]' , this.form.pictures[i] ? this.form.pictures[i] : '')
                    fd.append('forms[data]['+i+'][video]' , this.form.videos[i] ? this.form.videos[i] : '')
                    fd.append('forms[data]['+i+'][ratings]' , this.form.ratings[i] ? this.form.ratings[i] : 5)
                    fd.append('forms[data]['+i+'][reviews]' , this.form.reviews[i] ? this.form.reviews[i] : '')
                }
                this.save_button = false;
                $("#modal_load").fadeIn();
                apiCustomer.saveReviewProduct(fd).then( response => {
                   if(response.data.code == 200){
                    Message.alert('Review berhasil ditambahkan' , "Informasi" , 1500)
                    window.location.reload()
                   }else{
                       this.save_button = true;
                       Message.alert(response.data.message , "Informasi")//alert(response.data.message)
                   }
                   $("#modal_load").fadeOut();
                });
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
                $('#image-review').attr('src', '');
                $('#video-review').attr('src', '');
                document.getElementById("review-textarea").value = "";
                for(let x=1; x <= 5 ; x++){
                    $('.ratings--'+x+'--'+x).addClass('active')
                }
                this.form.pictures = []
                this.form.ratings = []
                this.form.products = []
                this.form.reviews = []
                this.form.videos = []
                this.form.inv = ''
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