<template>
   <div>
        <div id="refund_confirm_modal" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="notify-modal">
                            <img class="ilustrasi" src="/img/ilustrasi_refund.svg" alt="">
                            <h6 class="mtop-20">Kamu akan mengajukan pengembalian produk ini.</h6>
                            <h6>Apakah kamu yakin?</h6>
                            <div class="row-btn">
                                <button class="btn-secondary" @click="close_modal">Tidak, batalkan</button>
                                <button href="javascript:void(0)" @click="show_refund_form" class="btn-primary">Yakin, ajukan</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="refund_form_modal" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="refund">
                            <img class="ilustrasi" src="/img/ilustrasi_refund.svg" alt="">
                            <h6 class="text-center">Berikan alasan kenapa kamu mengajukan pengembalian barang ini ?</h6>
                            <textarea v-model="form.description" name="description" cols="25" rows="4" placeholder="beri alasan pengembalian"></textarea>
                            <h6 class="text-center m-auto w-70">Unggah foto dan video pesanan yang akan di refund</h6>
                            <div class="media-ulasan">
                                <div>
                                    <h6>foto</h6>
                                    <div class="foto mright-15">
                                        <div class="media-upload mright-10">
                                            <label for="img_1">
                                                <div class="upload-icon">
                                                    <img class="icon preview_photo_1" src="">
                                                </div>
                                            </label>
                                            <input @change="changePreview($event ,'1' , 'photo')" name="image_1" type="file" class="add-image" id="img_1">
                                        </div>
                                        <div class="media-upload mright-10">
                                            <label for="img_2">
                                                <div class="upload-icon">
                                                    <img class="icon preview_photo_2" src="">
                                                </div>
                                            </label>
                                            <input @change="changePreview($event ,'2' , 'photo')" name="image_2" type="file" class="add-image" id="img_2">
                                        </div>
                                        <div class="media-upload">
                                            <label for="img_3">
                                                <div class="upload-icon">
                                                    <img class="icon preview_photo_3" src="">
                                                </div>
                                            </label>
                                            <input @change="changePreview($event ,'3' , 'photo')" name="image_3" type="file" class="add-image" id="img_3">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="media-ulasan">
                                <div>
                                    <h6>video</h6>
                                    <div class="video">
                                        <div class="media-upload">
                                            <label for="img_4">
                                                <div class="upload-icon" @click="refs('video_1')" >
                                                    <video :class="'preview_video_1'" playsinline autoplay muted loop />
                                                </div>
                                            </label>
                                            <input id="video_1" @change="changePreview($event ,'1' , 'video')" :name="'video_1'" type="file" class="add-image" accept=".jpg,.jpeg.,.gif,.png,.mov,.mp4">
                                            <!-- <input id="video_1" type="file" class="add-image"> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <router-link to="/policies?index=Aturan_dan_Kebijakan">
                                <h6 class="tnc">syarat dan ketentuan pengembalian barang</h6>
                            </router-link>
                            <div class="option-refund">
                                <div class="input-radio">
                                    <div class="radio">
                                        <input type="radio" value="point" v-model="form.refund_type"  id="point"/>
                                        <label for="point"></label>
                                    </div>
                                    <div class="label">
                                        <img src="/img/ic_hot_point.svg" alt="">
                                        refund dengan hot point
                                    </div>
                                </div>
                                <div class="input-radio">
                                    <div class="radio">
                                        <input type="radio" value="cash" v-model="form.refund_type" id="cash" />
                                        <label for="cash"></label>
                                    </div>
                                    <div class="label mleft-10">
                                        refund dengan cash
                                    </div>
                                </div>
                            </div>
                            <div class="row-btn">
                                <button class="btn-secondary" @click="close_modal">batalkan</button>
                                <button href="javascript:void(0)" @click="submitForm" class="btn-primary">
                                    ajukan refund
                                </button>
                                <!-- <button href="javascript:void(0)" @click="show_refund_success" class="btn-primary">
                                    ajukan refund
                                </button> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="refund_success_modal" class="modal">
            <div class="modal-dialog w-350">
                <div class="modal-body">
                    <div class="content-modal">
                        <div class="notify-modal">
                            <img class="ilustrasi" src="/img/ilustrasi_refund.svg" alt="">
                            <h5 class="mtop-20">Terimakasih. Permintaan refund kamu akan segera kami proses</h5>
                            <button class="cont-shopping btn-primary" @click="redirect"> belanja kembali</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <div id="refund_confirm_modal_detail" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="refund">
                           <h5 class="text-center">Konfirmasi refund dengan mengirimkan detail informasi pengajuan refund</h5>
                            <div class="form-input">
                                <h6>nama</h6>
                                <input type="text" placeholder="nama">
                                <h6>nomor resi</h6>
                                <input type="text" placeholder="no resi">
                                <h6>logistik yang digunakan</h6>
                                <input type="text" placeholder="nama logistik">
                                <button class="btn-primary" @click="submitConfirmation">kirim</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
        <!-- <div id="refund_confirm_modal_bill" class="modal">
            <div class="modal-dialog w-400">
                <div class="modal-body">
                    <span class="close-modal"></span>
                    <div class="content-modal">
                        <div class="refund">
                            <h5 class="text-center">Konfirmasi refund dengan mengirimkan detail informasi rekening tujuan pengembalian dana</h5>
                            <div class="form-input">
                                <h6>atas nama</h6>
                                <input type="text" placeholder="nama penerima">
                                <h6>bank penerima</h6>
                                <input type="text" placeholder="bank tujuan">
                                <h6>nomor rekening bank penerima</h6>
                                <input type="text" placeholder="rekening tujuan">
                                <button class="btn-primary">ok</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->
   </div>
</template>
<script>
    import CloseModal from '../component/CloseModal.vue'
    import apiCustomer from '../../../apis/Customer'
    import Message from '../../../utils/Message'
    export default {
        name: "ModalRefund.vue",
        props: ['invoice'],
        data() {
            return {
                refund_btn : true,
                form : {
                    image_1 : '',
                    image_2 : '',
                    image_3 : '',
                    video_1 : '',
                    description : '',
                    refund_type : 'point'
                }
            }
        },
        methods: {
            close_modal(){
                $('.modal').fadeOut();
                $("body").removeClass('overflow-hidden');
            },
            refs(id){
                $( "input[name^='"+id+"']" ).click()
            },
            show_refund_form(){
                $("#refund_form_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                    $("#refund_form_modal").addClass('overflow-scroll');

                });
                 $("#refund_confirm_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            show_refund_success(){
                $("#refund_form_modal").fadeOut(function () {
                    $("body").addClass('overflow-hidden');
                });
                $("#refund_success_modal").fadeIn(function () {
                    $("body").addClass('overflow-hidden');
                });
            },
            redirect(){
                if(reload){
                    window.location.href = '/'
                }

            },
            changePreview(event, index , type){
                if(type == 'photo'){
                    if (event) {
                        $('.preview_photo_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        this.form['image_'+index] = event.target.files[0]
                    }
                }else if(type == 'video'){
                    if (event) {
                        $('.preview_video_'+index).attr('src' , URL.createObjectURL(event.target.files[0]));
                        this.form['video_'+index] = event.target.files[0]
                    }
                }
            },
            submitForm(){
                if(this.refund_btn == true){
                    this.refund_btn = false;
                    const fd = new FormData();
                    fd.append('invoice_number' , this.invoice)
                    fd.append('image_1' , this.form.image_1)
                    fd.append('image_2' , this.form.image_2)
                    fd.append('image_3' , this.form.image_3)
                    fd.append('video' , this.form.video_1)
                    fd.append('description' , this.form.description)
                    fd.append('refund_type' , this.form.refund_type)
                    apiCustomer.submitFormRefundTransaction(fd).then( response => {
                        if(response.data.code == 200){
                            this.show_refund_success();
                            window.location.reload();
                        }else{
                           
                            Message.alert(response.data.message, 'Informasi' , 1500);
                            //alert(response.data.message)
                        }
                         this.refund_btn = true;
                    });
                }
                
            }
        },
        components:{
                CloseModal,
                Message
        }
    }
</script>

<style>

</style>
