import type {MenuItem} from "@/Layouts/default-layout/config/types";
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const sidebarMenuConfig: Array<MenuItem> = [
    {
        pages: [
            {
                heading: t('sidebarMenu.dashboard'),
                route: "/dashboard",
                routePermissions: ["can-view-employee-chart"],
                keenthemesIcon: "element-11",
                bootstrapIcon: "bi-app-indicator",
            }
        ],
    },

    {
        heading: t('sidebarMenu.section.admin'),
        route: "/configurations",
        headingRoutes: ["/configurations", "/roles", "/users", "/branches"],
        headingPermissions: ["can-view-configuration", "can-view-role", "can-view-user", "can-view-branch"],
        pages: [
            {
                heading: t('sidebarMenu.configuration.menu'),
                route: "/configurations",
                routePermissions: ["can-view-configuration"],
                keenthemesIcon: "setting-2",
                bootstrapIcon: "bi-archive",
            },

            {
                sectionTitle: t('sidebarMenu.userManagement.menu'),
                route: "/users",
                keenthemesIcon: "profile-user",
                bootstrapIcon: "bi-archive",
                routeArray: ["/roles", "/users"],
                routePermissions: ["can-view-role", "can-view-user"],
                sub: [
                    {
                        heading: t('sidebarMenu.userManagement.submenu.roles'),
                        route: "/roles",
                        permission: "can-view-role",
                    },
                    {
                        heading: t('sidebarMenu.userManagement.submenu.users'),
                        route: "/users",
                        permission: "can-view-user",
                    },
                ],
            },

            {
                sectionTitle: t('sidebarMenu.branch.menu'),
                route: "/branches",
                keenthemesIcon: "element-plus",
                bootstrapIcon: "bi-archive",
                routeArray: ["/branches"],
                routePermissions: ["can-view-branch"],
                sub: [
                    {
                        heading: t('sidebarMenu.branch.submenu'),
                        route: "/branches",
                        permission: "can-view-branch",
                    },
                ],
            },
        ],
    },
    {
        heading: t('sidebarMenu.section.hr'),
        route: "/crafted",
        headingRoutes: ["/employees", "/job-positions", "/departments", "/leave-types", "/leave-applications", "/pending-leave-applications", "/attendance-requests", "/subscriptions"],
        headingPermissions: ["can-view-employee", "can-view-job-position", "can-view-department", "can-view-leave-type", "can-view-leave-application", "can-view-pending-leave-application", "can-view-attendance-request", "can-view-subscription"],
        pages: [
            {
                sectionTitle: t('sidebarMenu.employee.menu'),
                route: "/employees",
                keenthemesIcon: "user-square",
                bootstrapIcon: "bi-archive",
                routeArray: ["/employees", "/job-positions"],
                routePermissions: ["can-view-job-position", "can-view-employee"],
                sub: [
                    {
                        heading: t('sidebarMenu.employee.submenu.jobPosition'),
                        route: "/job-positions",
                        permission: "can-view-job-position",
                    },
                    {
                        heading: t('sidebarMenu.employee.submenu.employee'),
                        route: "/employees",
                        permission: "can-view-employee",
                    },

                ],
            },
            {
                sectionTitle: t('sidebarMenu.department.menu'),
                route: "/departments",
                keenthemesIcon: "office-bag",
                bootstrapIcon: "bi-archive",
                routeArray: ["/departments"],
                routePermissions: ["can-view-department"],
                sub: [
                    {
                        heading: t('sidebarMenu.department.submenu'),
                        route: "/departments",
                        permission: "can-view-department",
                    },
                ],
            },
            // {
            //     sectionTitle: t('sidebarMenu.leave.menu'),
            //     route: "/leave-types",
            //     keenthemesIcon: "row-horizontal",
            //     bootstrapIcon: "bi-archive",
            //     routeArray: ["/leave-types", "/leave-applications", "/pending-leave-applications"],
            //     routePermissions: ["can-view-leave-type", "can-view-leave-application", "can-view-pending-leave-application"],
            //     sub: [
            //         {
            //             heading: t('sidebarMenu.leave.submenu.leaveType'),
            //             route: "/leave-types",
            //             permission: "can-view-leave-type",
            //         },
            //         {
            //             heading: t('sidebarMenu.leave.submenu.leaveApplication'),
            //             route: "/leave-applications",
            //             permission: "can-view-leave-application",
            //         },
            //         {
            //             heading: t('sidebarMenu.leave.submenu.pendingLeaveApplication'),
            //             route: "/pending-leave-applications",
            //             permission: "can-view-pending-leave-application",
            //         },
            //     ],
            // },
            {
                sectionTitle: t('sidebarMenu.leave.menu.admin'),
                route: "/leave-types",
                keenthemesIcon: "time",
                bootstrapIcon: "bi-archive",
                routeArray: ["/leave-types", "/leave-allocations", "/public-holidays", "/calendar-leaves"],
                routePermissions: ["can-view-leave-type", "can-view-leave-allocation", "can-view-public-holiday", "can-view-calendar-leave"],
                sub: [
                    {
                        heading: t('sidebarMenu.leave.submenu.leaveType'),
                        route: "/leave-types",
                        permission: "can-view-leave-type",
                    },
                    {
                        heading: 'Leave Allocation',
                        route: "/leave-allocations",
                        permission: "can-view-leave-allocation",
                    },
                    {
                        heading: 'Public Holiday',
                        route: "/public-holidays",
                        permission: "can-view-public-holiday",
                    },
                    {
                        heading: 'Time dashboard',
                        route: "/calendar-leaves",
                        permission: "can-view-calendar-leave",
                    }
                ]
            },
            {
                heading: t('sidebarMenu.leaveRequests.menu'),
                route: "/leaves",
                routePermissions: ["can-view-leave"],
                keenthemesIcon: "notification-on",
                bootstrapIcon: "bi-archive",
            },
            {
                heading: t('sidebarMenu.attendanceRequests.menu'),
                route: "/attendance-requests",
                routePermissions: ["can-view-attendance-request"],
                keenthemesIcon: "calendar-tick",
                bootstrapIcon: "bi-archive",
            },
            {
                heading: t('sidebarMenu.subscription.menu'),
                route: "/subscriptions",
                routePermissions: ["can-view-subscription"],
                keenthemesIcon: "basket-ok",
                bootstrapIcon: "bi-archive",
            },
            // {
            //     sectionTitle: "account",
            //     route: "/account",
            //     keenthemesIcon: "profile-circle",
            //     bootstrapIcon: "bi-person",
            //     sub: [
            //         {
            //             heading: "accountOverview",
            //             route: "/crafted/account/overview",
            //         },
            //         {
            //             heading: "settings",
            //             route: "/crafted/account/settings",
            //         },
            //     ],
            // },

            // {
            //     sectionTitle: "authentication",
            //     keenthemesIcon: "fingerprint-scanning",
            //     bootstrapIcon: "bi-sticky",
            //     sub: [
            //         {
            //             sectionTitle: "basicFlow",
            //             sub: [
            //                 {
            //                     heading: "signIn",
            //                     route: "/sign-in",
            //                 },
            //                 {
            //                     heading: "signUp",
            //                     route: "/sign-up",
            //                 },
            //                 {
            //                     heading: "passwordReset",
            //                     route: "/password-reset",
            //                 },
            //             ],
            //         },
            //         {
            //             heading: "multiStepSignUp",
            //             route: "/multi-step-sign-up",
            //         },
            //         {
            //             heading: "error404",
            //             route: "/404",
            //         },
            //         {
            //             heading: "error500",
            //             route: "/500",
            //         },
            //     ],
            // },
            // {
            //     sectionTitle: "modals",
            //     route: "/modals",
            //     keenthemesIcon: "design",
            //     bootstrapIcon: "bi-shield-check",
            //     sub: [
            //         {
            //             sectionTitle: "general",
            //             route: "/general",
            //             sub: [
            //                 {
            //                     heading: "inviteFriends",
            //                     route: "/crafted/modals/general/invite-friends",
            //                 },
            //                 {
            //                     heading: "viewUsers",
            //                     route: "/crafted/modals/general/view-user",
            //                 },
            //                 {
            //                     heading: "upgradePlan",
            //                     route: "/crafted/modals/general/upgrade-plan",
            //                 },
            //                 {
            //                     heading: "shareAndEarn",
            //                     route: "/crafted/modals/general/share-and-earn",
            //                 },
            //             ],
            //         },
            //         {
            //             sectionTitle: "forms",
            //             route: "/forms",
            //             sub: [
            //                 {
            //                     heading: "newTarget",
            //                     route: "/crafted/modals/forms/new-target",
            //                 },
            //                 {
            //                     heading: "newCard",
            //                     route: "/crafted/modals/forms/new-card",
            //                 },
            //                 {
            //                     heading: "newAddress",
            //                     route: "/crafted/modals/forms/new-address",
            //                 },
            //                 {
            //                     heading: "createAPIKey",
            //                     route: "/crafted/modals/forms/create-api-key",
            //                 },
            //             ],
            //         },
            //         {
            //             sectionTitle: "wizards",
            //             route: "/wizards",
            //             sub: [
            //                 {
            //                     heading: "twoFactorAuth",
            //                     route: "/crafted/modals/wizards/two-factor-auth",
            //                 },
            //                 {
            //                     heading: "createApp",
            //                     route: "/crafted/modals/wizards/create-app",
            //                 },
            //                 {
            //                     heading: "createAccount",
            //                     route: "/crafted/modals/wizards/create-account",
            //                 },
            //             ],
            //         },
            //     ],
            // },
            // {
            //     sectionTitle: "widgets",
            //     route: "/widgets",
            //     keenthemesIcon: "element-7",
            //     bootstrapIcon: "bi-layers",
            //     sub: [
            //         {
            //             heading: "widgetsLists",
            //             route: "/crafted/widgets/lists",
            //         },
            //         {
            //             heading: "widgetsStatistics",
            //             route: "/crafted/widgets/statistics",
            //         },
            //         {
            //             heading: "widgetsCharts",
            //             route: "/crafted/widgets/charts",
            //         },
            //         {
            //             heading: "widgetsMixed",
            //             route: "/crafted/widgets/mixed",
            //         },
            //         {
            //             heading: "widgetsTables",
            //             route: "/crafted/widgets/tables",
            //         },
            //         {
            //             heading: "widgetsFeeds",
            //             route: "/crafted/widgets/feeds",
            //         },
            //     ],
            // },
        ],
    },
    {
        heading: t('sidebarMenu.section.employee'),
        route: "/employee-attendances",
        headingRoutes: ["/employee-attendances", "/employee-attendance-requests", "/employee-attendance-logs"],
        headingPermissions: ["can-view-employee-attendance", "can-view-employee-attendance-request", "can-view-employee-attendance-log", "can-view-employee-leave"],
        pages: [
            {
                heading: t('sidebarMenu.employeeAttendance.menu'),
                route: "/employee-attendances",
                routePermissions: ["can-view-employee-attendance"],
                keenthemesIcon: "calendar-tick",
                bootstrapIcon: "bi-archive",
            },
            {
                heading: t('sidebarMenu.leave.menu.employee'),
                route: "/employee-leaves",
                routePermissions: ["can-view-employee-leave"],
                keenthemesIcon: "time",
                bootstrapIcon: "bi-archive",
            },
            {
                heading: t('sidebarMenu.leaveRequests.menu'),
                route: "/employee-leave-requests",
                routePermissions: ["can-view-employee-leave"],
                keenthemesIcon: "notification-on",
                bootstrapIcon: "bi-archive",
            }
        ],
    },

    // {
    //     heading: "apps",
    //     route: "/apps",
    //     pages: [
    //         {
    //             sectionTitle: "customers",
    //             route: "/customers",
    //             keenthemesIcon: "abstract-38",
    //             bootstrapIcon: "bi-printer",
    //             sub: [
    //                 {
    //                     heading: "gettingStarted",
    //                     route: "/apps/customers/getting-started",
    //                 },
    //                 {
    //                     heading: "customersListing",
    //                     route: "/apps/customers/customers-listing",
    //                 },
    //                 {
    //                     heading: "customerDetails",
    //                     route: "/apps/customers/customer-details",
    //                 },
    //             ],
    //         },
    //         {
    //             sectionTitle: "subscriptions",
    //             route: "/subscriptions",
    //             keenthemesIcon: "basket",
    //             bootstrapIcon: "bi-cart",
    //             sub: [
    //                 {
    //                     heading: "getStarted",
    //                     route: "/apps/subscriptions/getting-started",
    //                 },
    //                 {
    //                     heading: "subscriptionList",
    //                     route: "/apps/subscriptions/subscription-list",
    //                 },
    //                 {
    //                     heading: "addSubscription",
    //                     route: "/apps/subscriptions/add-subscription",
    //                 },
    //                 {
    //                     heading: "viewSubscription",
    //                     route: "/apps/subscriptions/view-subscription",
    //                 },
    //             ],
    //         },
    //         {
    //             heading: "calendarApp",
    //             route: "/apps/calendar",
    //             keenthemesIcon: "calendar-8",
    //             bootstrapIcon: "bi-calendar3-event",
    //         },
    //         {
    //             sectionTitle: "chat",
    //             route: "/chat",
    //             keenthemesIcon: "message-text-2",
    //             bootstrapIcon: "bi-chat-left",
    //             sub: [
    //                 {
    //                     heading: "privateChat",
    //                     route: "/apps/chat/private-chat",
    //                 },
    //                 {
    //                     heading: "groupChat",
    //                     route: "/apps/chat/group-chat",
    //                 },
    //                 {
    //                     heading: "drawerChat",
    //                     route: "/apps/chat/drawer-chat",
    //                 },
    //             ],
    //         },
    //     ],
    // },
];

export default sidebarMenuConfig;
