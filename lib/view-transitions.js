// Disclaimer: Importing the `store` using a global is just a temporary solution.
const { store, directive, useContext, useLayoutEffect } =
	window.__experimentalInteractivity;

// data-wp-view-transitions-key--[option]
directive(
	'transition',
	({
		directives: { transition },
		context,
		evaluate,
		element,
		store: { state },
	}) => {
		const contextValue = useContext(context);
		Object.values(transition).forEach((key) => {
			const transitionKey = 'item-' + key;
			useLayoutEffect(() => {
				// If there is no Key, set it to the main option if exists.
				if (
					!state.core.navigation.viewTransitionKey &&
					element.props['data-wp-transition-main']
				) {
					state.core.navigation.viewTransitionKey = transitionKey;
					state.core.navigation.viewTransitionElement =
						element.ref.current;
				}

				// If the key matches, add transition. If not, remove it.
				if (state.core.navigation.viewTransitionKey === transitionKey) {
					element.ref.current.style.viewTransitionName =
						transitionKey;
				} else {
					element.ref.current.style.viewTransitionName = '';
				}

				return evaluate(key, { context: contextValue });
			});
		});
	}
);

store({
	state: {
		core: {
			navigation: {
				viewTransitionKey: null,
				viewTransitionElement: null,
			},
		},
	},
	actions: {
		core: {
			navigation: {
				addTransition: ({ ref, state }) => {
					// Reset the previous element transition
					if (state.core.navigation.viewTransitionElement)
						state.core.navigation.viewTransitionElement.style.viewTransitionName =
							'';

					// Get new key
					const transitionKey =
						'item-' + ref.getAttribute('data-wp-transition');

					ref.style.viewTransitionName = transitionKey;
					state.core.navigation.viewTransitionKey = transitionKey;
					state.core.navigation.viewTransitionElement = ref;
				},
			},
		},
	},
});
