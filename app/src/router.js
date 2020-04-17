import Vue from 'vue'
import Router from 'vue-router'

Vue.use(Router)

export default new Router({
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
    },
    {
      path: '/table/:tableid/:column',
      name: 'tablewithcolumn',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/table/:tableid/:column/:value',
      name: 'tablewithcolumnvalue',
      component: () => import(/* webpackChunkName: "tabledata" */ './views/TableData.vue'),
      props: true,
    },
    {
      path: '/structure/:tableid',
      name: 'structure',
      component: () => import(/* webpackChunkName: "tablestructure" */ './views/TableStructure.vue'),
      props: true,
    },
    {
      path: '/editrow/:tableid/:column/:rowid',
      name: 'editrow',
      component: () => import(/* webpackChunkName: "editrow" */ './views/EditRow.vue'),
      props: true,
    },
    {
      path: '/query',
      name: 'query',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
    },
    {
      path: '/query/history/:historyindex',
      name: 'queryhistory',
      component: () => import(/* webpackChunkName: "query" */ './views/Query.vue'),
      props: true,
    },
  ]
});

