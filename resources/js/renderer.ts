import SimpleTestsIndex from './components/SimpleTestsIndex.vue';
import Navigation from './components/Navigation.vue';
import PluginContextStore from './PluginContextStore';

export default (context: PluginContext) => {
    PluginContextStore.plugin = context;

    context.router.addRoutes([
        {
            name: 'simple-tests-index',
            path: '/simple-tests-index',
            component: SimpleTestsIndex,
        },
    ]);

    context.navigation.add(Navigation)
}