import Vue from 'vue'
import Router from 'vue-router'
import Store from './store'

Vue.use(Router)

const router = new Router({
  routes: [
    {
      path: '/server',
      alias: '/', //todo: change later to login screen
      name: 'server',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "server" */ './views/Server.vue')
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
      path: '/table/:tableid',
      name: 'table',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/table/:tableid/:column',
      name: 'tablewithcolumn',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/table/:tableid/:column/:comparetype/:value',
      name: 'tablewithcolumnvalue',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/structure/:tableid',
      name: 'structure',
      component: () => import(/* webpackChunkName: "tablestructure" */ './views/TableStructure.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/editrow/:tableid/:column/:rowid',
      name: 'editrow',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditRow.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/addrow/:tableid',
      name: 'addrow',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditRow.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/addtable',
      name: 'addtable',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditTable.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/query',
      name: 'query',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
    {
      path: '/query/history/:historyindex',
      name: 'queryhistory',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
      meta: {
        requiresActiveDatabase: true
      }
    },
  ]
});

router.beforeEach((to, from, next) => {
  // if this route requires active database
  if (to.matched.some(record => record.meta.requiresActiveDatabase)) {
    // redirect to database list if active database is not set
    if (typeof Store.state.activeDatabase === 'undefined' || Store.state.activeDatabase == '') {
      next({name: 'server'});
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router;
