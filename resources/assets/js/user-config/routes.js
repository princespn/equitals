import Vue from "vue";
import VueRouter from "vue-router";
import Guard from "./middleware";
import LoginComponent from "./../components/user/LoginComponent.vue";
import CommingsoonComponent from "./../components/user/CommingsoonComponent.vue";
import RegisterComponent from "./../components/user/RegisterComponent.vue";
import ResetPasswordComponent from "./../components/user/ResetPasswordComponent.vue";
import ForgotComponent from "./../components/user/ForgotComponent.vue";
import CurrencyAddressComponent from "./../components/user/CurrencyAddressComponent.vue";
import DashboardComponent from "./../components/user/DashboardComponent.vue";
import MyProfileComponent from "./../components/user/MyProfileComponent.vue";
import SecurityComponent from "./../components/user/SecurityComponent.vue";
import SupportChatComponent from "./../components/user/SupportChatComponent.vue";
import EditProfileComponent from "./../components/user/EditProfileComponent.vue";
import FAQComponent from "./../components/user/FAQComponent.vue";
import ChangePasswordComponent from "./../components/user/ChangePasswordComponent.vue";
import LevelViewComponent from "./../components/user/LevelViewComponent.vue";
import DirectUserListComponent from "./../components/user/DirectUserListComponent.vue";
import DirectIncomeReportComponent from "./../components/user/DirectIncomeReportComponent.vue";
import LevelIncomeReportComponent from "./../components/user/LevelIncomeReportComponent.vue";
import RoiIncomeReportComponent from "./../components/user/RoiIncomeReportComponent.vue";
import WithdrawalsReportComponent from "./../components/user/WithdrawalsReportComponent.vue";
import fundTransferReportComponent from "./../components/user/fundTransferReportComponent.vue";
import TransferTopupReportComponent from "./../components/user/TransferTopupReportComponent.vue";
import SelfTopupComponent from "./../components/user/SelfTopupComponent.vue";
import SelfTopupReportComponent from "./../components/user/SelfTopupReportComponent.vue";
import ManualTopupComponent from "./../components/user/ManualTopupComponent.vue";
import ManualTopupReportComponent from "./../components/user/ManualTopupReportComponent.vue";
import PendingRoiWithdrawalComponent from "./../components/user/PendingRoiWithdrawalComponent.vue";
import ConfirmedRoiWithdrawalComponent from "./../components/user/ConfirmedRoiWithdrawalComponent.vue";
import PendingWorkingWithdrawalComponent from "./../components/user/PendingWorkingWithdrawalComponent.vue";
import ConfirmedWorkingWithdrawalComponent from "./../components/user/ConfirmedWorkingWithdrawalComponent.vue";
import FundToFundTransferComponent from "./../components/user/FundToFundTransferComponent.vue";
import MakeRoiWithdrawalComponent from "./../components/user/MakeRoiWithdrawalComponent.vue";
import MakePassiveWithdrawalComponent from "./../components/user/MakePassiveWithdrawalComponent.vue";
import PassiveWalletWithdrawReportComponent from "./../components/user/PassiveWalletWithdrawReportComponent.vue";
import MakeWorkingWithdrawalComponent from "./../components/user/MakeWorkingWithdrawalComponent.vue";
import TransferTopupBalanceComponent from "./../components/user/TransferTopupBalanceComponent.vue";
import BalanceSummaryComponent from "./../components/user/BalanceSummaryComponent.vue";
import ConfirmedDepositeComponent from "./../components/user/ConfirmedDepositeComponent.vue";
import PendingDepositeComponent from "./../components/user/PendingDepositeComponent.vue";
import PendingAddDepositeComponent from "./../components/user/PendingAddDepositeComponent.vue";
import ExpiredAddDepositeComponent from "./../components/user/ExpiredAddDepositeComponent.vue";
import ConfirmDepositeComponent from "./../components/user/ConfirmDepositeComponent.vue";
import MakeDepositeComponent from "./../components/user/MakeDepositeComponent.vue";
import AllTransactionComponent from "./../components/user/AllTransactionComponent.vue";
import SupportComponent from "./../components/user/SupportComponent.vue";
import LevelIncomeRoiReportComponent from "./../components/user/LevelIncomeRoiReportComponent.vue";
import UplineIncomeReportComponent from "./../components/user/UplineIncomeReportComponent.vue";
import AwardReportComponent from './../components/user/AwardReportComponent.vue';
import ThankyouComponent from './../components/user/ThankyouComponent.vue';
import PromotionalComponent from './../components/user/PromotionalComponent.vue';
import PromotionalReportComponent from './../components/user/PromotionalReportComponent.vue';
import PromotionalIncomeReportComponent from './../components/user/PromotionalIncomeReportComponent.vue';
import FundRequestReportComponent from './../components/user/FundRequestReportComponent.vue';
import AddFundComponent from './../components/user/AddFundComponent.vue';
import BinaryIncomeReportComponent from './../components/user/BinaryIncomeReportComponent.vue';
import ResidualIncomeReportComponent from './../components/user/ResidualIncomeReportComponent.vue';
import AchievedMatchingBonusIncomeReportComponent from './../components/user/AchievedMatchingBonusIncomeReportComponent.vue';
import MatchingBonusIncomeReportComponent from './../components/user/MatchingBonusIncomeReportComponent.vue';
import TreeViewComponent from './../components/user/TreeViewComponent.vue';
import TreeViewNewComponent from './../components/user/TreeViewNewComponent.vue';
import TeamViewComponent from './../components/user/TeamViewComponent.vue';
import TopupReportComponent from './../components/user/TopupReportComponent.vue';
import WorkingToPurchaseWalletReport from './../components/user/WorkingToPurchaseWalletReport.vue';
import DexToPurchaseTransferReportComponent from './../components/user/DexToPurchaseTransferReportComponent.vue';
import DownlineTopupReportComponent from './../components/user/DownlineTopupReportComponent.vue';
import WorkingWalletWithdrawComponent from './../components/user/WorkingWalletWithdrawComponent.vue';
import PromotionalIncomeComponent from './../components/user/PromotionalIncomeComponent.vue';
import BannerComponent from './../components/user/BannerComponent.vue';
import FundAddressComponent from './../components/user/FundAddressComponent.vue';
import FranchiseIncomeReport from './../components/user/FranchiseIncomeReport.vue';
import AddFundOneComponent from './../components/user/AddFundOneComponent.vue';
import AddFundReportComponent from './../components/user/AddFundReportComponent.vue';
import AddFundPerfectMoneyReportComponent from './../components/user/AddFundPerfectMoneyReportComponent.vue';
import PendingFundReportComponent from './../components/user/PendingFundReportComponent.vue';
import CertificateComponent from './../components/user/CertificateComponent.vue';
import SupperMatchingBonusReport from './../components/user/SupperMatchingBonusReport.vue';
import FreedomClubReportComponent from './../components/user/FreedomClubReportComponent.vue';


/*import RegisterAfterLoginComponent from './../components/user/RegisterAfterLoginComponent.vue';
 */
import CreateUserStructureComponent from './../components/user/CreateUserStructureComponent.vue';
import RegisterAfterLoginComponent from './../components/user/RegisterAfterLoginComponent.vue';
import RankReportComponent from './../components/user/RankReportComponent.vue';
import DexdroneEarnComponent from './../components/user/DexdroneEarnComponent.vue';
import StructureReportComponent from './../components/user/StructureReportComponent.vue';
import RegistrationStructureReport from './../components/user/RegistrationStructureReport.vue';

import PMSuccessComponent from './../components/user/PMSuccessComponent.vue';
import PMFailureComponent from './../components/user/PMFailureComponent.vue';

import FundInvoiceComponent from './../components/user/FundInvoiceComponent.vue';
import RefundComponent from './../components/user/RefundComponent.vue';

import PresentationComponent from './../components/user/PresentationComponent.vue';
import ActivationComponent from './../components/user/ActivationComponent.vue';
import AddFundStartComponent from './../components/user/AddFundStartComponent.vue';
import AddFundMiddleComponent from './../components/user/AddFundMiddleComponent.vue';
import AllUserBalanceReportComponent from './../components/user/AllUserBalanceReportComponent.vue';
import PendingBalanceTransferRequestComponent from './../components/user/PendingBalanceTransferRequestComponent.vue';
import WorkingBalanceTransferReport from './../components/user/WorkingBalanceTransferReport.vue';
import WorkingBalanceReceiveReport from './../components/user/WorkingBalanceReceiveReport.vue';
import PurchaseToPurchaseReport from './../components/user/PurchaseToPurchaseReport.vue';

import AllUserPurchaseBalanceReportComponent from './../components/user/AllUserPurchaseBalanceReportComponent.vue';
import PendingPurchaseTransferRequestComponent from './../components/user/PendingPurchaseTransferRequestComponent.vue';
import PurchaseReceiveBalanceReport from './../components/user/PurchaseReceiveBalanceReport.vue';


import VoucherComponent from './../components/user/VoucherComponent.vue';
import VoucherDetailComponent from './../components/user/VoucherDetailComponent.vue';
import VoucherPaymentComponent from './../components/user/VoucherPaymentComponent.vue';
import VoucherCheckoutComponent from './../components/user/VoucherCheckoutComponent.vue';

import CartComponent from "./../components/user/CartComponent.vue";

//import CheckoutComponent from "./../components/user/CheckoutComponent.vue";
import OrderHistoryComponent from "./../components/user/OrderHistoryComponent.vue";
import OrderDetailsComponent from "./../components/user/OrderDetailsComponent.vue";
import VoucherTransaction from "./../components/user/VoucherTransaction.vue";


//--- CoinPhaseComponent

import CoinPhaseComponent from "./../components/user/CoinPhaseComponent.vue";
import BuyCoinComponent from "./../components/user/BuyCoinComponent.vue";
import IcoPurchaseRep from "./../components/user/IcoPurchaseRep.vue";
import DownlineTokenReport from "./../components/user/DownlineTokenReport.vue";


//-- flight booking 
import TravelComponent from "./../components/user/travel/TravelComponent.vue";

import TravelDetailsComponent from "./../components/user/travel/TravelDetailsComponent.vue";
import TravelOrderHistory from "./../components/user/travel/TravelOrderHistory.vue";
import TravelTransactionReport from "./../components/user/travel/TravelTransactionReport.vue";
//---- hotel

import HotelOrderHistory from "./../components/user/travel/HotelOrderHistory.vue";
import HotelBooking from "./../components/user/travel/HotelBooking.vue";
import HotelBookingDetails1 from "./../components/user/travel/HotelBookingDetails1.vue";
import HotelBookingPayment from "./../components/user/travel/HotelBookingPayment.vue";

// coin transfer
import CoinTransferToUser from "./../components/user/CoinTransferToUser.vue";
import TermsComponent from "./../components/user/TermsComponent.vue";



export default new VueRouter({
    base: "/user/",
    routes: [{
            path: "*",
            redirect: '/login',
            name: "login",
            component: LoginComponent,
            beforeEnter: Guard.guest
        },

        {
            path: "/login",
            name: "login",
            component: LoginComponent,
            beforeEnter: Guard.guest
        },
        {
            path: "/comming",
            name: "comming",
            component: CommingsoonComponent,
            beforeEnter: Guard.guest
        },
        {
            path: "/terms",
            name: "terms",
            component: TermsComponent,
            beforeEnter: Guard.guest
        },


        {
            path: "/register",
            name: "register",
            component: RegisterComponent,
            // beforeEnter: Guard.guest
        },
        {
            path: "/forgot-password",
            name: "forgot-password",
            component: ForgotComponent,
            beforeEnter: Guard.guest
        },
        {
            path: "/reset-password",
            name: "reset-password",
            component: ResetPasswordComponent,
            beforeEnter: Guard.guest
        },
        {
            path: "/currency-address",
            name: "currencyAddress",
            component: CurrencyAddressComponent,
            // beforeEnter: Guard.guest
        },
        {
            path: "/voucher",
            name: "voucher",
            component: VoucherComponent,
            beforeEnter: Guard.auth,

        },

        {
            path: "/voucher-detail/:id",
            name: "voucher-detail",
            component: VoucherDetailComponent,
            beforeEnter: Guard.auth,

        },

        {
            path: "/payment",
            name: "payment",
            component: VoucherPaymentComponent,
            beforeEnter: Guard.auth,
        },
        {
            path: "/checkout",
            name: "checkout",
            component: VoucherCheckoutComponent,
            beforeEnter: Guard.auth,

        },
        {
            path: '/voucher-transaction',
            component: VoucherTransaction,
            name: 'voucher-transaction',
            beforeEnter: Guard.auth,

        },

        {
            path: "/cart",
            name: "cart",
            component: CartComponent,
            beforeEnter: Guard.auth
        },


        {
            path: '/order-history',
            name: 'order-history',
            component: OrderHistoryComponent,
        },
        {
            path: '/order-details/:id',
            name: 'order-details',
            component: OrderDetailsComponent,
        },

        {
            path: "/travel",
            name: "travel",
            component: TravelComponent,
            beforeEnter: Guard.auth,
        },
        {
            path: "/travel-details/:data",
            name: "travel-details",
            component: TravelDetailsComponent,
            beforeEnter: Guard.auth,
        },
        {
            path: "/travel-transaction-report",
            name: "travel-transaction-report",
            component: TravelTransactionReport,
            beforeEnter: Guard.auth,
        },

        {
            path: "/travel-order-history",
            name: "travel-order-history",
            component: TravelOrderHistory,
            beforeEnter: Guard.auth,
        },
        {
            path: "/hotel",
            name: "hotel",
            component: HotelBooking,
            beforeEnter: Guard.auth,
        },


        {
            path: "/hotel-details/:data",
            name: "hotel-details",
            component: HotelBookingDetails1,
            beforeEnter: Guard.auth,
        },
        {
            path: "/hotel-payment",
            name: "hotel-payment",
            component: HotelBookingPayment,
            beforeEnter: Guard.auth,
        },
        {
            path: "/dashboard",
            name: "dashboard",
            component: DashboardComponent,
            beforeEnter: Guard.auth
        },
        {
            path: '/thankyou',
            component: ThankyouComponent,
            name: 'thankyou',
            /*  beforeEnter: Guard.guest */
        },
        {
            path: "/my-profile",
            name: "my-profile",
            component: MyProfileComponent,
            beforeEnter: Guard.auth,
            /*  meta: {
                title: "Profile",
                breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                  { name: "Profile", link: "my-profile" },
                  { name: "My Profile", link: "my-profile" }
                ]
              }*/
        },
        {
            path: "/security",
            name: "security",
            component: SecurityComponent,
            beforeEnter: Guard.auth,
            /*  meta: {
                title: "Profile",
                breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                  { name: "Profile", link: "security" },
                  { name: "My Profile", link: "security" }
                ]
              }*/
        },

        {
            path: "/hotel-order-history",
            name: "hotel-order-history",
            component: HotelOrderHistory,
            beforeEnter: Guard.auth,
        },

        {
            path: "/hotel-details/:data",
            name: "hotel-details",
            component: HotelBookingDetails1,
            beforeEnter: Guard.auth,
        },
        {
            path: "/hotel-payment",
            name: "hotel-payment",
            component: HotelBookingPayment,
            beforeEnter: Guard.auth,
        },
        {
            path: "/dashboard",
            name: "dashboard",
            component: DashboardComponent,
            beforeEnter: Guard.auth
        },
        {
            path: '/thankyou',
            component: ThankyouComponent,
            name: 'thankyou',
            beforeEnter: Guard.guest
        },
        {
            path: "/my-profile",
            name: "my-profile",
            component: MyProfileComponent,
            beforeEnter: Guard.auth,
            /*  meta: {
                title: "Profile",
                breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                  { name: "Profile", link: "my-profile" },
                  { name: "My Profile", link: "my-profile" }
                ]
              }*/
        },
        {
            path: "/security",
            name: "security",
            component: SecurityComponent,
            beforeEnter: Guard.auth,
            /*  meta: {
                title: "Profile",
                breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                  { name: "Profile", link: "security" },
                  { name: "My Profile", link: "security" }
                ]
              }*/
        },

        {
            path: "/support-chat",
            name: "support-chat",
            component: SupportChatComponent,
            beforeEnter: Guard.auth,
            /*  meta: {
                title: "Profile",
                breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                  { name: "Profile", link: "security" },
                  { name: "My Profile", link: "security" }
                ]
              }*/
        },
        {
            path: "/edit-profile",
            name: "edit-profile",
            component: EditProfileComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Edit Profile",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Profile", link: "edit-profile" },
                 { name: "Edit Profile", link: "edit-profile" }
               ]
             }*/
        },
        {
            path: "/faq",
            name: "faq",
            component: FAQComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "FAQ",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Faq", link: "Faq" },
                 { name: "FAQ", link: "FAQ" }
               ]
             }*/
        },
        {
            path: "/change-password",
            name: "change-password",
            component: ChangePasswordComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Change Password",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Profile", link: "change-password" },
                { name: "Change Password", link: "change-password" }
              ]
            }*/
        },
        /*  {
            path: "/make-deposite",
            name: "make-deposite",
            component: MakeDepositeComponent,
            beforeEnter: Guard.auth,
            meta: {
              title: "Make Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposit", link: "make-deposite" },
                { name: "Make Payments", link: "make-deposite" }
              ]
            }
          },*/
        {
            path: "/pending-deposite",
            name: "pending-deposite",
            component: PendingDepositeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Ongoing Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposit", link: "pending-deposite" },
                { name: "Ongoing Payments", link: "pending-deposite" }
              ]
            }*/
        },
        {
            path: "/pending-add-deposite",
            name: "pending-add-deposite",
            component: PendingAddDepositeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Ongoing Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposit", link: "pending-deposite" },
                { name: "Ongoing Payments", link: "pending-deposite" }
              ]
            }*/
        },

        {
            path: "/expired-add-deposite",
            name: "expired-add-deposite",
            component: ExpiredAddDepositeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Ongoing Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposit", link: "pending-deposite" },
                { name: "Ongoing Payments", link: "pending-deposite" }
              ]
            }*/
        },
        {
            path: "/confirm-deposite",
            name: "confirm-deposite",
            component: ConfirmDepositeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Ongoing Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposit", link: "pending-deposite" },
                { name: "Ongoing Payments", link: "pending-deposite" }
              ]
            }*/
        },
        {
            path: "/confirmed-deposite",
            name: "confirmed-deposite",
            component: ConfirmedDepositeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Confirmed Payments",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Deposite", link: "confirmed-deposite" },
                { name: "Confirmed Payments", link: "confirmed-deposite" }
              ]
            }*/
        },
        {
            path: "/level-view",
            name: "level-view",
            component: LevelViewComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Level View",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "My Team", link: "level-view" },
                { name: "Level View", link: "level-view" }
              ]
            }*/
        },
        {
            path: "/direct-user-list",
            name: "direct-user-list",
            component: DirectUserListComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct User List",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "My Team", link: "direct-user-list" },
                { name: "Direct User List", link: "direct-user-list" }
              ]
            }*/
        },
        {
            path: "/tree-view",
            name: "tree-view",
            component: TreeViewComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct User List",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "My Team", link: "direct-user-list" },
                { name: "Direct User List", link: "direct-user-list" }
              ]
            }*/
        },

        {
            path: "/tree-view-new",
            name: "tree-view-new",
            component: TreeViewNewComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct User List",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "My Team", link: "direct-user-list" },
                { name: "Direct User List", link: "direct-user-list" }
              ]
            }*/
        },
        {
            path: "/team-view",
            name: "team-view",
            component: TeamViewComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct User List",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "My Team", link: "direct-user-list" },
                { name: "Direct User List", link: "direct-user-list" }
              ]
            }*/
        },
        {
            path: "/direct-income-report",
            name: "direct-income-report",
            component: DirectIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct Referral Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "direct-income-report" },
                { name: "Direct Referral Income Report", link: "direct-income-report" }
              ]
            }*/
        },
        {
            path: "/level-income-report",
            name: "level-income-report",
            component: LevelIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Level Referral Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "level-income-report" },
                { name: "Level Referral Income Report", link: "level-income-report" }
              ]
            }*/
        },
        {
            path: "/binary-income-report",
            name: "binary-income-report",
            component: BinaryIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "binary-income-report" },
                { name: "Binary Income Report", link: "binary-income-report" }
              ]
            }*/
        },
        {
            path: "/residual-income-report",
            name: "residual-income-report",
            component: ResidualIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "residual Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "residual-income-report" },
                { name: "residual Income Report", link: "residual-income-report" }
              ]
            }*/
        },
        {
            path: "/achieved-matching-bonus-income-report",
            name: "achieved-matching-bonus-income-report",
            component: AchievedMatchingBonusIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "matching-binary-bonus-income-report" },
                { name: "Binary Income Report", link: "matching-binary-bonus-income-report" }
              ]
            }*/
        },

        {
            path: "/matching-bonus-income-report",
            name: "matching-bonus-income-report",
            component: MatchingBonusIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "matching-binary-bonus-income-report" },
                { name: "Binary Income Report", link: "matching-binary-bonus-income-report" }
              ]
            }*/
        },
        {
            path: "/franchise-income-report",
            name: "franchise-income-report",
            component: FranchiseIncomeReport,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct Referral Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "direct-income-report" },
                { name: "Direct Referral Income Report", link: "direct-income-report" }
              ]
            }*/
        },
        {
            path: "/promotional-income",
            name: "promotional-income",
            component: PromotionalIncomeComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "binary-income-report" },
                { name: "Binary Income Report", link: "binary-income-report" }
              ]
            }*/
        },
        {
            path: "/promotional-report",
            name: "promotional-report",
            component: PromotionalReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "binary-income-report" },
                { name: "Binary Income Report", link: "binary-income-report" }
              ]
            }*/
        },
        {
            path: "/banners",
            name: "banners",
            component: BannerComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "binary-income-report" },
                { name: "Binary Income Report", link: "binary-income-report" }
              ]
            }*/
        },
        {
            path: "/presentation",
            name: "presentation",
            component: PresentationComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Binary Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "binary-income-report" },
                { name: "Binary Income Report", link: "binary-income-report" }
              ]
            }*/
        },


        {
            path: "/level-income-roi-report",
            name: "level-income-roi-report",
            component: LevelIncomeRoiReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Team ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "level-income-roi-report" },
                { name: "Team ROI Income Report", link: "level-income-roi-report" }
              ]
            }*/
        },
        {
            path: '/award-report',
            component: AwardReportComponent,
            name: 'award-report',
            beforeEnter: Guard.auth,
            /* meta: {
             title :'Award Report',    
             breadcrumb: [
                  { name: "Dashboard", link: "dashboard" },
                   { name: "Income Report", link: "level-income-report" },
                   { name: 'Award Report', link: 'award-report' },
                 
                 ]
             }*/
        },

        {
            path: "/upline-income-report",
            name: "upline-income-report",
            component: UplineIncomeReportComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Upline Income Report",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Income Report", link: "upline-income-report" },
                 { name: "Upline Income Report", link: "upline-income-report" }
               ]
             }*/
        },
        {
            path: "/roi-income-report",
            name: "roi-income-report",
            component: RoiIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/supper-matching-bonus-report",
            name: "supper-bonus",
            component: SupperMatchingBonusReport,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/freedom-club-report",
            name: "freedon-club-report",
            component: FreedomClubReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/dexdrone-earn-report",
            name: "dexdrone-earn-report",
            component: DexdroneEarnComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/structure-report",
            name: "structure-report",
            component: StructureReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/RegistrationStructureReport",
            name: "RegistrationStructureReport",
            component: RegistrationStructureReport,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "ROI Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "roi-income-report" },
                { name: "ROI Income Report", link: "roi-income-report" }
              ]
            }*/
        },
        {
            path: "/transfer-topup-balance",
            name: "transfer-topup-balance",
            component: TransferTopupBalanceComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Fund Transfer",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Fund Transfer", link: "fund-transfer" },
                { name: "Fund Transfer", link: "fund-transfer" }
              ]
            }*/
        },
        {
            path: "/transfer-topup-report",
            name: "transfer-topup-report",
            component: TransferTopupReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Fund Transfer Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Fund Transfer", link: "fund-transfer-report" },
                { name: "Fund Transfer Report", link: "fund-transfer-report" }
              ]
            }*/
        },
        {
            path: "/self-topup",
            name: "self-topup",
            component: SelfTopupComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Self Topup",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Topup Wallet", link: "self-topup" },
                { name: "Self Topup", link: "self-topup" }
              ]
            }*/
        },
        /*{
            path: "/manual-topup",
            name: "manual-topup",
            component: ManualTopupComponent,
            beforeEnter: Guard.auth,
        },
        {
            path: "/manual-topup-report",
            name: "manual-topup-report",
            component: ManualTopupReportComponent,
            beforeEnter: Guard.auth,
        },*/
        {
            path: "/self-topup-report",
            name: "self-topup-report",
            component: SelfTopupReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Self Topup Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Topup Wallet", link: "self-topup-report" },
                { name: "Self Topup Report", link: "self-topup-report" }
              ]
            }*/
        },
        {
            path: "/fund-address",
            name: "fund-address",
            component: FundAddressComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Self Topup",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Topup Wallet", link: "self-topup" },
                { name: "Self Topup", link: "self-topup" }
              ]
            }*/
        },
        {
            path: "/fund-report",
            name: "fund-report",
            component: SelfTopupReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Self Topup Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Topup Wallet", link: "self-topup-report" },
                { name: "Self Topup Report", link: "self-topup-report" }
              ]
            }*/
        },
        {
            path: "/make-roi-withdrawal",
            name: "make-roi-withdrawal",
            component: MakeRoiWithdrawalComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Make ROI Withdrawal",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "ROI Withdrawal", link: "make-roi-withdrawal" },
                 { name: "Make ROI Withdrawal", link: "make-roi-withdrawal" }
               ]
             }*/
        },
        {
            path: "/pending-roi-withdrawal",
            name: "pending-roi-withdrawal",
            component: PendingRoiWithdrawalComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Pending ROI Withdrawal",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "ROI Withdrawal", link: "pending-roi-withdrawal" },
                { name: "Pending ROI Withdrawal", link: "pending-roi-withdrawal" }
              ]
            }*/
        },
        {
            path: "/confirmed-roi-withdrawal",
            name: "confirmed-roi-withdrawal",
            component: ConfirmedRoiWithdrawalComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Confirmed ROI Withdrawal",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "ROI Withdrawal", link: "confirmed-roi-withdrawal" },
                { name: "Confirmed ROI Withdrawal", link: "confirmed-roi-withdrawal" }
              ]
            }*/
        },
        {
            path: "/make-working-withdrawal",
            name: "make-working-withdrawal",
            component: MakeWorkingWithdrawalComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Make Working Withdrawal",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Working Withdrawal", link: "make-working-withdrawal" },
                { name: "Make Working Withdrawal", link: "make-working-withdrawal" }
              ]
            }*/
        },

        {
            path: "/working-wallet-withdraw",
            name: "working-wallet-withdraw",
            component: WorkingWalletWithdrawComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Ongoing Withdrawal",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Withdrawal", link: "pending-working-withdrawal" },
                 {
                   name: "Ongoing Withdrawal",
                   link: "pending-working-withdrawal"
                 }
               ]
             }*/
        },
        {
            path: "/pending-working-withdrawal",
            name: "pending-working-withdrawal",
            component: PendingWorkingWithdrawalComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Ongoing Withdrawal",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Withdrawal", link: "pending-working-withdrawal" },
                 {
                   name: "Ongoing Withdrawal",
                   link: "pending-working-withdrawal"
                 }
               ]
             }*/
        },
        {
            path: "/confirmed-working-withdrawal",
            name: "confirmed-working-withdrawal",
            component: ConfirmedWorkingWithdrawalComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Confirmed Withdrawal",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Withdrawal", link: "confirmed-working-withdrawal" },
                {
                  name: "Confirmed Withdrawal",
                  link: "confirmed-working-withdrawal"
                }
              ]
            }*/
        },
        {
          path: "/fund-to-fund-transfer",
          name: "fund-to-fund-transfer",
          component: FundToFundTransferComponent,
          beforeEnter: Guard.auth,
          /*meta: {
            title: "Confirmed Withdrawal",
            breadcrumb: [
              { name: "Dashboard", link: "dashboard" },
              { name: "Withdrawal", link: "confirmed-working-withdrawal" },
              {
                name: "Confirmed Withdrawal",
                link: "confirmed-working-withdrawal"
              }
            ]
          }*/
      },
        {
            path: "/all-transaction",
            name: "all-transaction",
            component: AllTransactionComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "All Transaction",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "All Transaction", link: "all-transaction" }
              ]
            }*/
        },
        {
            path: "/balance-summary",
            name: "balance-summary",
            component: BalanceSummaryComponent,
            beforeEnter: Guard.auth,
            /* meta: {
               title: "Balance Summary",
               breadcrumb: [
                 { name: "Dashboard", link: "dashboard" },
                 { name: "Balance Summary", link: "balance-summary" }
               ]
             }*/
        },
        {
            path: "/support",
            name: "support",
            component: SupportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Support",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Support", link: "support" }
              ]
            }*/
        },
        {
            path: "/add-promotional",
            name: "add-promotional",
            component: PromotionalComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Promotional",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Add Promotional", link: "add-promotional" }
              ]
            }*/
        },
        {
            path: "/promotional-report",
            name: "promotional-report",
            component: PromotionalReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Promotional Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Promotional Report", link: "promotional-report" }
              ]
            }*/
        },
        {
            path: "/promotional-income-report",
            name: "promotional-income-report",
            component: PromotionalIncomeReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Promotional Inocme Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Promotional Income Report", link: "promotional-income-report" }
              ]
            }*/
        },
        {
            path: '/fund-request-report',
            component: FundRequestReportComponent,
            name: 'fund-request-report',
            beforeEnter: Guard.auth,
            /* meta: {
                  title :'INR Payment Report',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "INR Payment Report", link: "fund-request-report" }
                  ]  
                
                  }*/
        },
        {
            path: '/add-fund',
            component: AddFundComponent,
            name: 'add-fund',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },
        {
            path: '/topup-report',
            component: TopupReportComponent,
            name: 'topup-report',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        }, {
            path: '/downline-topup-report',
            component: DownlineTopupReportComponent,
            name: 'downline-topup-report',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },

        {
            path: '/withdrawals-income-report',
            component: WithdrawalsReportComponent,
            name: 'withdrawals-income-report',
            beforeEnter: Guard.auth,
        },
        {
          path: '/fund-transfer-report',
          component: fundTransferReportComponent,
          name: 'fund-transfer-report',
          beforeEnter: Guard.auth,
      },
        {
            path: '/passive-withdraw-report',
            component: PassiveWalletWithdrawReportComponent,
            name: 'passive-withdraw-report',
            beforeEnter: Guard.auth,
        },
        {
            path: "/certificate",
            name: "certificate",
            component: CertificateComponent,
            beforeEnter: Guard.auth,
        },

        {
            path: '/activation',
            component: ActivationComponent,
            name: 'activation',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },

        {
            path: '/add-fund-one',
            component: AddFundOneComponent,
            name: 'add-fund-one',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },
        {
            path: '/add-fund-start',
            component: AddFundStartComponent,
            name: 'add-fund-one',
            beforeEnter: Guard.auth,
        },

        {
            path: '/add-fund-middle',
            component: AddFundMiddleComponent,
            name: 'add-fund-one',
            beforeEnter: Guard.auth,
        },

        {
            path: '/add-fund-report',
            component: AddFundReportComponent,
            name: 'add-fund-report',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },
        {
            path: '/add-fund-perfectmoney-report',
            component: AddFundPerfectMoneyReportComponent,
            name: 'add-fund-perfectmoney-report',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },
        {
            path: '/pending-fund-report',
            component: PendingFundReportComponent,
            name: 'pending-fund-report',
            beforeEnter: Guard.auth,
            /*meta: {
                  title :'Add Fund',  
                  breadcrumb:[
                   { name: "Dashboard", link: "dashboard" },
                { name: "Add Fund", link: "add-fund" }
                  ]  
                
                  }*/
        },

        {
            path: "/registerafterlogin",
            name: "registerafterlogin",
            component: RegisterAfterLoginComponent,
            beforeEnter: Guard.auth
        },
        {
            path: "/all-user-balance-report",
            name: "alluserbalancereport",
            component: AllUserBalanceReportComponent,
            beforeEnter: Guard.auth
        },
        {
            path: "/pending-balance-transfer-requests",
            name: "pendingbalancetransferrequests",
            component: PendingBalanceTransferRequestComponent,
            beforeEnter: Guard.auth
        },
        {
            path: "/transfer-balance-report",
            name: "transferbalancereport",
            component: WorkingBalanceTransferReport,
            beforeEnter: Guard.auth
        },
        {
            path: "/receive-balance-report",
            name: "receivebalancereport",
            component: WorkingBalanceTransferReport,
            beforeEnter: Guard.auth
        },
        {
            path: "/all-user-purchase-balance-report",
            name: "alluserpurchasebalancereport",
            component: AllUserPurchaseBalanceReportComponent,
            beforeEnter: Guard.auth
        },
        {
            path: "/pending-purchase-transfer-requests",
            name: "pendingpurchasetransferrequests",
            component: PendingPurchaseTransferRequestComponent,
            beforeEnter: Guard.auth
        },
        {
            path: "/purchase-receive-balance-report",
            name: "purchasereceivebalancereport",
            component: PurchaseReceiveBalanceReport,
            beforeEnter: Guard.auth
        },
        {
            path: "/purchase-to-purchase-report",
            name: "purchasetopurchasereport",
            component: PurchaseToPurchaseReport,
            beforeEnter: Guard.auth
        },
        {
            path: "/rank-report",
            name: "rank-details-report",
            component: RankReportComponent,
            beforeEnter: Guard.auth,
            /*meta: {
              title: "Direct Referral Income Report",
              breadcrumb: [
                { name: "Dashboard", link: "dashboard" },
                { name: "Income Report", link: "direct-income-report" },
                { name: "Direct Referral Income Report", link: "direct-income-report" }
              ]
            }*/

        },
        // {
        //     path: "/create-structure",
        //     name: "user-create-structure",
        //     component: CreateUserStructureComponent,
        //     beforeEnter: Guard.auth,
        //     meta: {
        //       title: "Direct Referral Income Report",
        //       breadcrumb: [
        //         { name: "Dashboard", link: "dashboard" },
        //         { name: "Income Report", link: "direct-income-report" },
        //         { name: "Direct Referral Income Report", link: "direct-income-report" }
        //       ]
        //     }

        // },
        //---- coin Phase 

        {
            path: "/coin-phase",
            name: "coin-phase",
            component: CoinPhaseComponent,
            beforeEnter: Guard.auth,

        },

        {
            path: "/buy-coin/:id",
            name: "buy-coin",
            component: BuyCoinComponent,
            beforeEnter: Guard.auth,

        },
        {
            path: "/purchase-coin-rep",
            name: "purchase-coin-rep",
            component: IcoPurchaseRep,
            beforeEnter: Guard.auth,

        },
        {
            path: "/working-to-purchase-report",
            name: "working-to-purchase-report",
            component: WorkingToPurchaseWalletReport,
            beforeEnter: Guard.auth,

        },
        {
            path: "/working-wallet-to-purchase-report",
            name: "working-wallet-to-purchase-report",
            component: DexToPurchaseTransferReportComponent,
            beforeEnter: Guard.auth,

        },
        {
            path: "/coin-transfer-to-user",
            name: "coin-transfer-to-user",
            component: CoinTransferToUser,
            beforeEnter: Guard.auth,

        },
        {
            path: "/make-passive-withdrawal",
            name: "make-passive-withdrawal",
            component: MakePassiveWithdrawalComponent,
            beforeEnter: Guard.auth,
        },
        {
            path: "/success-pm-transaction",
            name: "success-pm-transaction",
            component: PMSuccessComponent,
            beforeEnter: Guard.auth,

        },
        {
            path: "/failed-pm-transaction",
            name: "failed-pm-transaction",
            component: PMFailureComponent,
            beforeEnter: Guard.auth,

        },
        {
            path: "/payment-invoice-status/:invoice_id",
            name: "payment-invoice-status-id",
            component: FundInvoiceComponent,
            beforeEnter: Guard.auth,
            meta: {
                title: "payment-invoice-status-id",
            }
        },

        {
            path: "/payment-invoice-status/:invoice_id",
            name: "payment-invoice-status",
            component: FundInvoiceComponent,
            beforeEnter: Guard.auth,
            meta: {
                title: "payment-invoice-status",
            }
        },
        {
            path: '/refund-payment/:transaction_id',
            name: 'refund-payment',
            component: RefundComponent,
            // beforeEnter: Guard.guest
        },
        /*{
            path: "/downline-token-report",
            name: "downline-token-report",
            component: DownlineTokenReport,
            beforeEnter: Guard.auth,
        },*/
    ]
});