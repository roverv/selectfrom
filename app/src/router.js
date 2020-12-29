import Vue from 'vue'
import Router from 'vue-router'
import Store from './store'

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/',
      name: 'login',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Login.vue'),
      meta: {
        requiresNoAuthentication: true
      }
    },
    {
      path: '/logout',
      name: 'logout',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Logout.vue'),
      meta: {
        requiresNoAuthentication: true
      }
    },
    {
      path: '/server',
      name: 'server',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Server.vue')
    },
    {
      path: '/settings',
      name: 'settings',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Settings.vue')
    },
    {
      path: '/help',
      name: 'help',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Help.vue')
    },
    {
      path: '/database/:database',
      name: 'database',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "database" */ './views/Database.vue'),
      props: true,
    },
    {
      path: '/adddatabase',
      name: 'adddatabase',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "database" */ './views/EditDatabase.vue'),
      props: true,
    },
    {
      path: '/editdatabase/:database',
      name: 'editdatabase',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "database" */ './views/EditDatabase.vue'),
      props: true,
    },
    {
      path: '/:database/table/:tableid',
      name: 'table',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/:database/table/:tableid/:column',
      name: 'tablewithcolumn',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/:database/table/:tableid/:column/:comparetype/:value',
      name: 'tablewithcolumnvalue',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/:database/structure/:tableid',
      name: 'structure',
      component: () => import(/* webpackChunkName: "tablestructure" */ './views/TableStructure.vue'),
      props: true,
    },
    {
      path: '/:database/indexes/:tableid',
      name: 'indexes',
      component: () => import(/* webpackChunkName: "tablestructure" */ './views/TableIndexes.vue'),
      props: true,
    },
    {
      path: '/:database/addindex/:tableid',
      name: 'addindex',
      component: () => import(/* webpackChunkName: "editindex" */ './views/EditIndex.vue'),
      props: true,
    },
    {
      path: '/:database/editindex/:tableid/:indexname',
      name: 'editindex',
      component: () => import(/* webpackChunkName: "editindex" */ './views/EditIndex.vue'),
      props: true,
    },
    {
      path: '/:database/foreignkeys/:tableid',
      name: 'foreignkeys',
      component: () => import(/* webpackChunkName: "tablestructure" */ './views/TableForeignKeys.vue'),
      props: true,
    },
    {
      path: '/:database/addforeignkey/:tableid',
      name: 'addforeignkey',
      component: () => import(/* webpackChunkName: "editforeignkey" */ './views/EditForeignKey.vue'),
      props: true,
    },
    {
      path: '/:database/editforeignkey/:tableid/:foreignkeyname',
      name: 'editforeignkey',
      component: () => import(/* webpackChunkName: "editforeignkey" */ './views/EditForeignKey.vue'),
      props: true,
    },
    {
      path: '/:database/editrow/:tableid/:column/:rowid',
      name: 'editrow',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditRow.vue'),
      props: true,
    },
    {
      path: '/:database/addrow/:tableid',
      name: 'addrow',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditRow.vue'),
      props: true,
    },
    {
      path: '/:database/addtable',
      name: 'addtable',
      component: () => import(/* webpackChunkName: "edittable" */ './views/EditTable.vue'),
      props: true,
    },
    {
      path: '/:database/edittable/:tableid',
      name: 'edittable',
      component: () => import(/* webpackChunkName: "edittable" */ './views/EditTable.vue'),
      props: true,
    },
    {
      path: '/:database/addview',
      name: 'addview',
      component: () => import(/* webpackChunkName: "editview" */ './views/EditView.vue'),
      props: true,
    },
    {
      path: '/:database/editview/:tableid',
      name: 'editview',
      component: () => import(/* webpackChunkName: "editview" */ './views/EditView.vue'),
      props: true,
    },
    {
      path: '/:database/query',
      name: 'query',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
    },
    {
      path: '/:database/query/history/:historyindex',
      name: 'queryhistory',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
    },
  ]
});

router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresNoAuthentication)) {
    next()
  } else {
    // redirect if not logged in or no csrf token is set
    if (typeof Store.state.authenticated === 'undefined' || Store.state.authenticated !== true) {
      next({name: 'login'});
    // redirect if no csrf token is set
    } else if(typeof Store.state.csrf_token === 'undefined' || Object.keys(Store.state.csrf_token).length === 0) {
      next({name: 'login'});
    }
    else {
      next()
    }
  }
})

export default router;
