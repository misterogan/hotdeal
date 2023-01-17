import Vue from 'vue';
import VueRouter from 'vue-router'
import VueAnalytics from 'vue-analytics';
Vue.use(VueRouter);
Vue.use(VueAnalytics, {
    id: 'G-40J5HFEYRC',
    checkDuplicatedScript: true
})

import Home from '../components/desktop/Home'
import Main from '../components/desktop/Main'
import Login from '../components/desktop/auth/Login'
import Register from '../components/desktop/auth/Register'
import RegisterInvite from '../components/desktop/auth/RegisterInvite'
import PersonalInfo from '../components/desktop/PersonalInfo'
import PersonalInfoAddress from '../components/desktop/PersonalInfoAddress'
import PersonalInfoEdit from '../components/desktop/PersonalInfoEdit'
import AccountForm from '../components/desktop/component/profile/AccountForm'
import AddressForm from '../components/desktop/component/profile/AddressForm'
import PasswordForm from '../components/desktop/component/profile/PasswordForm'
import TransactionList from '../components/desktop/TransactionList'
import ListTransaction from '../components/desktop/component/order/OrderTransactionList'
import PendingTransaction from '../components/desktop/component/order/PendingTransactionList'
import RefundTransactionList from '../components/desktop/component/order/RefundTransactionList'
import CancelTransactionList from '../components/desktop/component/order/CancelTransactionList'
import ProductDetail from '../components/desktop/ProductDetail'
import PromoList from '../components/desktop/PromoList'
import Wishlist from '../components/desktop/Wishlist'
import Cart from '../components/desktop/Cart'
import Checkout from '../components/desktop/Checkout'
import Notification from '../components/desktop/Notification'
import LuckyWinner from '../components/desktop/LuckyWinner'
import AboutUs from '../components/desktop/AboutUs'
import HelpCenter from '../components/desktop/HelpCenter'
import NotFound from '../components/desktop/NotFound'
import FLashSaleDetail from '../components/desktop/FlashSaleDetail'
import AllProduct from '../components/desktop/AllProduct'
import CategoryResults from '../components/desktop/CategoryResults.vue'
import productBundling from '../components/desktop/ProductBundling'
import PrivacyPolicy from '../components/desktop/PrivacyPolicy'
import RefundPolicy from '../components/desktop/RefundPolicy'
import ShippingPolicy from '../components/desktop/ShippingPolicy'
import TermsOfService from '../components/desktop/TermsOfService'
import DetailProduct from '../components/desktop/DetailProduct'
import Hotpoint from  '../components/desktop/HotPoint'
import Invite from '../components/desktop/Invite'
import RejekiNomplok from  '../components/desktop/RejekiNomplok'
import RejekiNomplokPeriode from '../components/desktop/RejekiNomplokPeriode'
import RejekiNomplokPemenang from '../components/desktop/RejekiNomplokPemenang'
import EmailNotifUser from '../components/desktop/EmailNotifUser'
import EmailNotifUserBody from '../components/desktop/EmailNotifUserBody'
import EmailNotifVendor from '../components/desktop/EmailNotifVendor'
import KuponRejeki from  '../components/desktop/KuponRejeki'
import PaymentSuccess from '../components/desktop/PaymentSuccess'
import PaymentPending from '../components/desktop/PaymentPending'
import PaymentFailed from '../components/desktop/PaymentFailed'
import News from '../components/desktop/News'
import HotpointVoucher from '../components/desktop/HotpointVoucher'
import SuccessRedeem from '../components/desktop/SuccessRedeem'
import Bundling from '../components/desktop/Bundling'
import HistoryReferral from '../components/desktop/HistoryReferral'
import RejekiNomplokAbout from '../components/desktop/RejekiNomplokAbout'
import RejekiNomplokInfo from '../components/desktop/RejekiNomplokInfo'
import RejekiNomplokProduct from '../components/desktop/RejekiNomplokProduct'
import RejekiNomplokListWinner from '../components/desktop/RejekiNomplokListWinner'

// SELLER COMPONENT
import SellerLayout from '../components/vendor/layout/Layout'
import SellerLogin from '../components/vendor/views/SignIn'
import SellerDashboard from '../components/vendor/views/Dashboard'
import SellerProductList from '../components/vendor/views/ProductList'
import SellerProductCreate from '../components/vendor/views/ProductCreate'

// MERCHANT COMPONENT
import DetailMerchant from '../components/merchant/DetailMerchant'
import ProductMerchant from '../components/merchant/ProductMerchant'
import ReviewMerchant from '../components/merchant/ReviewMerchant'
import Merchant from '../components/merchant/Merchant'

//VENDOR COMPONENT
import VendorLayout from '../components/seller/VendorLayout'
import VendorDashboard from '../components/seller/Dashboard'
import VendorProductList from '../components/seller/ProductList'
import VendorProductCreate from '../components/seller/ProductCreate'
import VendorProductCreateVariation from '../components/seller/ProductCreateVariation'
import VendorProductEdit from '../components/seller/ProductEdit'
import VendorOrderList from '../components/seller/OrderList'
import VendorOrderListRefund from '../components/seller/OrderListRefund'
import VendorDeliveryRules from '../components/seller/DeliveryRules'
import VendorAddressStore from '../components/seller/AddressStore'
import VendorAddressStoreEdit from '../components/seller/AddressStoreEdit'
import VendorAccountStore from '../components/seller/AccountStore'
import VendorAccountStoreEdit from '../components/seller/AccountStoreEdit'
import VendorRefundDetail from '../components/seller/OrderListRefundDetail'
import VendorNotification from '../components/seller/VendorNotification'
import { Role } from '../utils/Role';
import User from '../apis/User'
import Forget from "../components/desktop/auth/Forget";
import Verification from "../components/desktop/auth/Verification";
import VerifiedEmail from "../components/desktop/auth/VerifiedEmail";
import PaymentSuccsess from "../components/desktop/PaymentSuccess"
import Maintenance from '../components/desktop/component/config/Maintenance'

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            component: Main,
            children: [
                {
                    path:'/rejeki-nomplok',
                    name : 'rejeki-nomplok-about',
                    meta : {
                        title: 'Hotdeal.id | No #1 Video Commerce di Indonesia',
                        breadcrumb: [
                            { name: 'Rejeki Nomplok About', link: '' },
                        ]},
                    component : RejekiNomplokAbout
                },
                {
                    path:'/info-rejeki-nomplok',
                    name : 'rejeki-nomplok-info',
                    meta : {
                        title: 'Hotdeal.id | No #1 Video Commerce di Indonesia',
                        breadcrumb: [
                            { name: 'Rejeki Nomplok Info', link: '' },
                        ]},
                    component : RejekiNomplokInfo
                },
                {
                    path:'/product-rejeki-nomplok',
                    name : 'rejeki-nomplok-product',
                    meta : {
                        title: 'Hotdeal.id | No #1 Video Commerce di Indonesia',
                        breadcrumb: [
                            { name: 'Rejeki Nomplok Product', link: '' },
                        ]},
                    component : RejekiNomplokProduct
                },
                {
                    path:'/winner-rejeki-nomplok',
                    name : 'rejeki-nomplok-winner',
                    meta : {
                        title: 'Hotdeal.id | No #1 Video Commerce di Indonesia',
                        breadcrumb: [
                            { name: 'Rejeki Nomplok Winner List', link: '' },
                        ]},
                    component : RejekiNomplokListWinner
                },

                {
                    path:'',
                    name : 'home',
                    meta : {
                        title: 'Hotdeal.id | No #1 Video Commerce di Indonesia',
                        breadcrumb: [
                            { name: 'Beranda', link: '' },
                        ]},
                    component : Home
                },
                {
                    path:'/product-category',
                    name : 'results-category',
                    meta : {
                        title: 'Product Results - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Produk', link: '' },
                        ]},
                    component : CategoryResults
                },
                {
                    path:'/flash-sale',
                    name : 'flashsale',
                    meta : {
                        title: 'Flash Sale - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Flash sale', link: '' },
                        ]},
                    component : FLashSaleDetail
                },
                {
                    path: '/personal-info',
                    name: 'personalinfo',
                    meta : {
                        title: 'Informasi Personal - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Profil Saya', link: '/personal-info' },
                        ],
                        authorize: [ Role.MustLogin ],
                        },
                    component: PersonalInfo
                },
                {
                    path:'/product',
                    name : 'product',
                    meta : {
                        title: 'Product - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Beranda', link: '' },
                            { name: 'Hasil Pencarian', link: '/product?search=' },
                        ]},
                    component : AllProduct
                },
                {
                    path:'/bundling/product',
                    name : 'productbundling',
                    meta : {
                        title: 'Product Bundling - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Produk Bundling', link: '' },
                        ]},
                    component : productBundling
                },
                {
                    path: '/bundling',
                    name: 'bundling',
                    meta : {
                        title: 'Special Event - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Event', link: '' },
                            { name: 'Event', link: '/bundling' },
                        ],
                        authorize: [ Role.Guest ]
                        },
                    component: Bundling
                },
                
                {
                    path: '/hotpoint',
                    name: 'hotpoint',
                    meta : {
                        title: 'Hot Point - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Hot Point', link: '/hotpoint' },
                        ], 
                        authorize: [ Role.MustLogin ]
                    },
                    component: Hotpoint
                },
                {
                    path: '/invite-friends',
                    name: 'invite',
                    meta : {
                        title: 'Invite Friends - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Undang Teman', link: '/invite' },
                        ],
                        authorize: [ Role.MustLogin ]
                    },
                    component: Invite
                },
                {
                    path: '/history-refferal',
                    name: 'historyrefferal',
                    meta : {
                        title: 'History Referral - Hotdeal.id',
                        breadcrumb: [
                            { name: 'History Referral', link: '' },
                        ]},
                    component: HistoryReferral
                },
                {
                    path: '/kupon-rejeki',
                    name: 'kuponrejeki',
                    meta : {
                        title: 'Kupon Rejeki - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Kupon Rejeki', link: '/kupon-rejeki' },
                        ]},
                    component: KuponRejeki
                },
                {
                    path: '/personal-info-address',
                    name: 'personalinfoaddress',
                    meta : {
                        title: 'Informasi Alamat - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Daftar Alamat', link: '/personal-info-address' },
                        ],
                        authorize: [ Role.MustLogin ]
                    },
                    component: PersonalInfoAddress
                },
                {
                    path: '/personal-info-edit',
                    name: 'personalinfoedit',
                    meta : {
                        title: 'Edit Informasi Personal - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                            { name: 'Profil Saya', link: '/personal-info' },
                            { name: 'Ubah', link: '' },
                    ],
                    authorize: [ Role.MustLogin ]},
                    component : PersonalInfoEdit,
                    children: [
                        {
                            path:'account',
                            name: 'editpersonalinfo',
                            meta : {
                                title: 'Informasi Personal - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Akun', link: '' },
                                    { name: 'Edit Akun', link: '/personal-info-edit/account' },
                            ],authorize: [ Role.MustLogin ]},
                            component : AccountForm
                        },
                        {
                            path:'password',
                            name : 'password',
                            meta : {
                                title: 'Halaman Keamanan - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Akun', link: '' },
                                    { name: 'Keamanan', link: '/personal-info-edit/password' },
                                ],authorize: [ Role.MustLogin ]},
                            component : PasswordForm
                        },
                    ]
                },
                {
                    path: '/transactions',
                    name: 'transactions',
                    meta : {
                        title: 'Transaksi - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Daftar Transaksi', link: '' },
                        ],authorize: [ Role.MustLogin ]},
                    component: TransactionList,
                    children: [
                        {
                            path:'list-transaction',
                            name: 'list-transaction',
                            meta : {
                                title: 'Daftar Transaksi - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Akun', link: '' },
                                    { name: 'Daftar Transaksi', link: '/transactions/list-transaction' },
                                ],authorize: [ Role.MustLogin ]},
                            component : ListTransaction
                        },
                        {
                            path:'pending-transaction',
                            name : 'pending-transaction',
                            meta : {
                                title: 'Transaksi Pending - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Akun', link: '' },
                                    { name: 'Belum Bayar', link: '/transactions/pending-transaction' },
                                ],authorize: [ Role.MustLogin ]},
                            component : PendingTransaction
                        },
                        {
                            path:'refund-transaction',
                            name : 'refundtransaction',
                            meta : {
                                title: 'Daftar Refund - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Akun', link: '' },
                                    { name: 'Refund', link: '/transactions/refund-transaction' },
                                ],authorize: [ Role.MustLogin ]},
                            component : RefundTransactionList
                        },
                        {
                            path:'cancel-transaction',
                            name : 'canceltransaction',
                            meta : {
                                title: 'Daftar Pembatalan - Hotdeal.id',
                                breadcrumb: [
                                    { name: 'Transaksi', link: '' },
                                    { name: 'Dibatalkan', link: '/cancel-transaction' },
                                ],authorize: [ Role.MustLogin ]},
                            component : CancelTransactionList
                        }
                    ]
                },
                {
                    path: '/product-detail/',
                    name: 'productdetails',
                    component: ProductDetail,
                    children: [
                        {
                            path :'*',
                            name : 'productdetail',
                            meta : {
                                breadcrumb: [
                                    { name: 'Detail Produk', link: '' },
                                ]},
                            component : DetailProduct
                        }
                    ]
                },
                {
                    path: '/news-detail/',
                    name: 'newsdetail',
                    component: News,
                    children: [
                        {
                            path :'*',
                            meta : {
                                breadcrumb: [
                                    { name: 'Detail Berita', link: '' },
                                ]},
                            component : News
                        }
                    ]
                },
                {
                    path: '/promo-list',
                    name: 'promolist',
                    meta : {
                        title: 'Daftar Promosi - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Daftar Promo', link: '' },
                        ]},
                    component: PromoList
                },
                {
                    path: '/wishlist',
                    name: 'wishlist',
                    meta : {
                        title: 'Halaman Wishlist - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Wishlist', link: '' },
                        ],
                        authorize: [ Role.MustLogin ]
                    },
                    component: Wishlist
                },
                {
                    path: '/cart',
                    name: 'cart',
                    meta : {
                        title: 'Daftar Keranjang - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Keranjang', link: '' },
                        ],
                        authorize: [ Role.MustLogin ]
                        },
                    component: Cart
                },
                {
                    path: '/checkout',
                    name: 'checkout',
                    meta : {
                        title: 'Checkout - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Keranjang', link: '' },
                            { name: 'Checkout', link: '/checkout' },
                        ],authorize: [ Role.MustLogin ]},
                    component: Checkout
                },
                {
                    path: '/notification',
                    name: 'notification',
                    meta : {
                        title: 'Notifikasi - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Notifikasi', link: '' },
                        ],authorize: [ Role.MustLogin ]},
                    component: Notification
                },
                // {
                //     path: '/lucky-winner',
                //     name: 'Lucky-Winner',
                //     meta : {
                //         title: 'Lucky Winner - Hotdeal.id',
                //         breadcrumb: [
                //             { name: 'Lucky Winner', link: '/lucky-winner' },
                //         ]},
                //     component: LuckyWinner
                // },
                // {
                //     path: '/rejeki-nomplok',
                //     name: 'rejekinomplok',
                //     meta : {
                //         title: 'Rejeki Nomplok - Hotdeal.id',
                //         breadcrumb: [
                //             { name: 'Rejeki Nomplok', link: '' },
                //     ]},
                //     component: RejekiNomplok
                // },
                {
                    path: '/payment-success',
                    name: 'paymentsuccess',
                    meta : {
                        title: 'Pembayaran Berhasil - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Pembayaran Berhasil', link: '' },
                        ]},
                    component: PaymentSuccess,
                },
                {
                    path: '/payment-pending',
                    name: 'paymentpending',
                    meta : {
                        title: 'Lanjutkan Pembayaran - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Lanjutkan Pembayaran', link: '' },
                        ]},
                    component: PaymentPending,
                },
                {
                    path: '/payment-failed',
                    name: 'paymentfailed',
                    meta : {
                        title: 'Pembayaran Gagal - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Pembayaran Gagal', link: '' },
                        ]},
                    component: PaymentFailed,
                },
                {
                    path: '/period-list',
                    name: 'periodlist',
                    meta : {
                        title: 'Periode Rejeki Nomplok - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Periode Pemenang Rejeki Nomplok', link: '' },
                        ]},
                    component: RejekiNomplokPeriode,
                },
                {
                    path: '/winner-list',
                    name: 'winnerlist',
                    meta : {
                        title: 'Pemenang Rejeki Nomplok - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Daftar Pemenang Rejeki Nomplok', link: '' },
                        ]},
                    component: RejekiNomplokPemenang,
                },
                {
                    path: '/about-us',
                    name: 'aboutus',
                    meta : {
                        title: 'Tentang - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Tentang Kami', link: '' },
                        ]},
                    component: AboutUs
                },
                {
                    path: '/help-center',
                    name: 'helpcenter',
                    meta : {
                        title: 'Bantuan - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Pusat Bantuan', link: '' },
                        ]},
                    component: HelpCenter
                },
                {
                    path: '/news',
                    name: 'news',
                    meta : {
                        title: 'News - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Berita', link: '/news' },
                        ]},
                    component: News
                },
                {
                    path: '/policies',
                    name: 'privacypolicy',
                    meta : {
                        title: 'Kebijakan Privasi - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Aturan', link: '' },
                            { name: 'Kebijakan Privasi', link: '/policies' },
                        ]},
                    component: PrivacyPolicy
                },
                {
                    path: '/policies/refund-policy',
                    name: 'refundpolicy',
                    meta : {
                        title: 'Kebijakan Pengembalian Barang - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Aturan', link: '' },
                            { name: 'Kebijakan Pengembalian Barang & Dana', link: '/policies/refund-policy' },
                        ]},
                    component: RefundPolicy
                },
                {
                    path: '/policies/shipping-policy',
                    name: 'shippingpolicy',
                    meta : {
                        title: 'Ketentuan Pengiriman - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Aturan', link: '' },
                            { name: 'Ketentuan Pengiriman', link: '/policies/shipping-policy' },
                        ]},
                    component: ShippingPolicy
                },
                {
                    path: '/policies/terms-of-service',
                    name: 'termsofservice',
                    meta : {
                        title: 'Persyaratan Layanan - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Aturan', link: '' },
                            { name: 'Persyaratan Layanan', link: '/policies/terms-of-service' },
                        ]},
                    component: TermsOfService
                },
                {
                    path: '/email',
                    name: 'email',
                    meta : {
                        title: 'Email - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Email', link: '/email' },
                        ]},
                    component: EmailNotifUser
                },
                {
                    path: '/email-body',
                    name: 'emailbody',
                    meta : {
                        title: 'Email Body - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Email Body', link: '/email-body' },
                        ]},
                    component: EmailNotifUserBody
                },
                {
                    path: '/email-vendor',
                    name: 'emailvendor',
                    meta : {
                        breadcrumb: [
                            { name: 'Email Vendor', link: '/email-vendor' },
                        ]},
                    component: EmailNotifVendor
                },
                {
                    path: '/payment/success',
                    name: 'PaymentSuccess',
                    meta : {
                        title: 'Pembayaran Berhasil - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Pesanan', link: '/transactions' },
                        ]},
                    component: PaymentSuccsess
                },
                {
                    path: '/hotpoint-voucher',
                    name: 'HotpointVoucher',
                    meta : {
                        title: 'Hot Point Voucher - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Hot point Voucher', link: '' },
                        ]},
                    component: HotpointVoucher
                },
                {
                    path: '/success-redeem',
                    name: 'SuccessRedeem',
                    meta : {
                        title: 'Success Redeem - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Success Redeem', link: '/success-redeem' },
                        ]},
                    component: SuccessRedeem
                },
                {
                    path: '/merchant-detail',
                    name: 'merchantdetail',
                    meta : {
                        title: 'Merchant Detail - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Detail Merchant', link: '' },
                        ]},
                    component: DetailMerchant
                },
                {
                    path: '/merchant-product',
                    name: 'merchantproduct',
                    meta : {
                        title: 'Produk Merchant- Hotdeal.id',
                        breadcrumb: [
                            { name: 'Product Merchant', link: '' },
                        ]},
                    component: ProductMerchant
                },
                {
                    path: '/merchant/*',
                    name: 'merchantproduct',
                    meta : {
                        title: 'Produk Merchant- Hotdeal.id',
                        breadcrumb: [
                            { name: 'Product Merchant', link: '' },
                        ]},
                    component: Merchant
                },
                {
                    path: '/merchant-review',
                    name: 'merchantreview',
                    meta : {
                        title: 'Review Merchant - Hotdeal.id',
                        breadcrumb: [
                            { name: 'Review Merchant', link: '' },
                        ]},
                    component: ReviewMerchant
                },
            ]
        },
        {
            path: '/seller',
            component: SellerLayout,
            children: [
                {
                    path:'login',
                    name : 'SellerLogin',
                    component : SellerLogin
                },
                {
                    path:'dashboard',
                    name : 'Seller Dashboard',
                    meta: { authorize: [ Role.Vendor ] },
                    component : SellerDashboard
                },
                {
                    path:'product/list',
                    name : 'Seller ProductList',
                    meta: { authorize: [ Role.Vendor ] },
                    component : SellerProductList
                },
                {
                    path:'product/create',
                    name : 'Seller ProductCreate',
                    meta: { authorize: [ Role.Vendor ] },
                    component : SellerProductCreate
                },
            ]
        },
        {
            path: '/vendor',
            component: VendorLayout,
            meta: { authorize: [ Role.Vendor ] },
            children: [
                {
                    path:'dashboard',
                    name : 'VendorDashboard',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Gambaran Umum', link: '' },
                        ] },
                    component : VendorDashboard
                },
                {
                    path:'notification-seller',
                    name : 'VendorNotification',
                    meta: { authorize: [ Role.Vendor ], 
                        breadcrumb: [
                            { name: 'Vendor Notification', link: '' },
                        ] },
                    component : VendorNotification
                },
                {
                    path:'product/list',
                    name : 'ProductList',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Daftar Produk', link: '' },
                        ] },
                    component : VendorProductList
                },
                {
                    path:'product/create',
                    name : 'ProductCreate',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Tambah Produk', link: '' },
                        ] },
                    component : VendorProductCreateVariation
                },
                {
                    path:'product/edit/*',
                    name : 'ProductEdit',
                    meta: { authorize: [ Role.Vendor ], 
                        breadcrumb: [
                            { name: 'Ubah Produk', link: '' },
                        ] },
                    component : VendorProductEdit,
                    children: [
                        {
                            path :'*',
                        }
                    ]
                },
                {
                    path:'product/create/variation',
                    name : 'ProductCreateVariation',
                    meta: { authorize: [ Role.Vendor ] },
                    component : VendorProductCreateVariation
                },
                {
                    path:'order/list',
                    name : 'OrderList',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Daftar Pesanan', link: '' },
                        ] },
                    component : VendorOrderList
                },
                {
                    path:'order/list/refund',
                    name : 'OrderListRefund',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Daftar Pengembalian', link: '' },
                        ] },
                    component : VendorOrderListRefund
                },
                {
                    path:'order/list/refund/detail',
                    name : 'OrderListRefundDetail',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Detail Pengembalian', link: '' },
                        ] },
                    component : VendorRefundDetail
                },
                {
                    path:'delivery/rules',
                    name : 'DeliveryRules',
                    meta: { authorize: [ Role.Vendor ] },
                    component : VendorDeliveryRules
                },
                {
                    path:'store/address',
                    name : 'AddressStore',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Alamat Toko', link: '' },
                        ] 
                    },
                    component : VendorAddressStore
                },
                {
                    path:'store/address/edit',
                    name : 'AddressStoreEdit',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Ubah Alamat Toko', link: '' },
                        ]  },
                    component : VendorAddressStoreEdit
                },
                {
                    path:'store/account',
                    name : 'AccountStore',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Akun', link: '' },
                        ] },
                    component : VendorAccountStore
                },
                {
                    path:'store/account/edit',
                    name : 'AccountStoreEdit',
                    meta: { authorize: [ Role.Vendor ],
                        breadcrumb: [
                            { name: 'Ubah Akun', link: '' },
                        ] },
                    component : VendorAccountStoreEdit
                },

            ]
        },
        {
            path: '/login',
            name: 'login',
            meta : {
                title: 'Halaman Login - Hotdeal.id',
            },
            component: Login
        },
        {
            path: '/register',
            name: 'register',
            component: Register,
            meta : {
                title: 'Halaman Registrasi - Hotdeal.id',
            },
        },
        {
            path: '/invite/*',
            name: 'registerinvite',
            component: RegisterInvite,
            meta : {
                title: 'Halaman Registrasi Invite - Hotdeal.id',
            },
        },
        {
            path: '/forget',
            name: 'forget',
            meta : {
                title: 'Lupa Password - Hotdeal.id',
            },
            component: Forget
        },
        {
            path: '/verification',
            name: 'verification',
            meta : {
                title: 'Register Verifikasi - Hotdeal.id',
            },
            component: Verification
        },
        {
            path: '/verified-email',
            name: 'verified',
            meta : {
                title: 'Verifikasi Email - Hotdeal.id',
            },
            component: VerifiedEmail
        },
        {
            path:'/maintenance',
            name : 'maintenance',
            meta : {
                title: 'Sedang Dalam perbaikan',
            },
            component: Maintenance
        },
        {
            path: '/:NotFound(.*)*',
            name: 'notfound',
            meta : {
                title: 'Halaman Tidak Ditemukan - Hotdeal.id',
            },
            component: NotFound,
        },
    ],
    scrollBehavior (to, from, savedPosition) {
        return { x: 0, y: 0 };
    }
});
export default router;
router.beforeEach((to, from, next) => {
    $("body").removeClass('overflow-hidden');
    // console.log(to)
    // let maintenance = true;
    
    // if(maintenance && to.name != 'maintenance'){
    //     return next({path : '/maintenance'})
    // }
        
    const { authorize } = to.meta;
    if(localStorage.getItem('auth')){
        User.profile().then(response =>{
            let Userdata = response.data;
            if (authorize == 'Vendor') {

                if(response.status === 401){
                    return next({ path: '/login', query: { returnUrl: to.path } });
                }
                if(Userdata.is_vendor == true){
                    next();
                }else{
                    return next({ path: '/login', query: { returnUrl: to.path } });
                }
            }else if(authorize == 'MustLogin'){
                if(!localStorage.getItem('auth')){
                    return next({ path: '/login', query: { returnUrl: to.path } });
                }else{
                    next();
                }
            }else{
                next();
            }
        });
    }else{
        if(authorize == 'MustLogin'){
            return next({ path: '/login', query: { returnUrl: to.path } });
        }else{
            if (authorize == 'Vendor') {
                if(!localStorage.getItem('auth')){
                    return next({ path: '/login', query: { returnUrl: to.path } });
                }
            }
            next();
        }

    }
})

router.afterEach((to, from) => {
    document.title = to.meta && to.meta.title ? to.meta.title : '';
});
