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
      component: () => import(/* webpackChunkName: "server" */ './views/Login.vue')
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
      path: '/database/:database',
      name: 'database',
      // route level code-splitting
      // this generates a separate chunk (about.[hash].js) for this route
      // which is lazy-loaded when the route is visited.
      component: () => import(/* webpackChunkName: "database" */ './views/Database.vue'),
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

export default router;
