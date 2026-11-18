Your Vue developers get Capell pages as Vue 3 components, so they build on what Capell sends instead of writing a page client from scratch. Vue is the adapter Capell Inertia uses until told otherwise. Choosing it needs no setting, only installing it.

The build needs Vue 3.5, Inertia's Vue library and Vite's Vue integration, and the adapter declares all three for you. Page and widget HTML is cleaned on the server before Vue draws it. The components carry class names such as capell-inertia-page but no styles, so your stylesheet decides the look.

Three widget types have components of their own, which are content, image and title. Any other type is drawn by the content component, and that shows only its title and content. A page shows its title, its content and every layout container, with each widget drawn by its component.
