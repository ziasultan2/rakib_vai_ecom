/** When your routing table is too long, you can split it into small modules**/
import Layout from '@/layout';

const productRoutes = {
  path: '/product',
  component: Layout,
  redirect: '/product/list',
  name: 'Product',
  meta: {
    title: 'Product',
    icon: 'example',
  },
  children: [
    {
      path: 'list',
      component: () => import('@/views/product/index'),
      name: 'ProductList',
      meta: { title: 'ProductList', icon: 'list' },
    },
  ],
};

export default productRoutes;
