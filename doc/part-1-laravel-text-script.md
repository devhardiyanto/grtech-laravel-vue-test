```
LARAVEL TEST
```
Create a basic Laravel project to manage company with their employees that has
the following:

1. The project will have a basic **Laravel Authentication** , with a default account
    to log in as administrator (use middleware to control page authentication).
2. Use **Laravel Breeze** starter kit **(using InertiaJS + Vue 3 + Tailwind)** for the
    authentication but remove the register functionality.
3. Use database **migration seeds** to create these users, using these credentials:
**Email** : admin@grtech.com | **Password** : password
**Email** : user@grtech.com | **Password** : password
4. Use middleware auth to protect route companies & employees from being
accessed by user@grtech.com
5. The project will consist of basic **CRUD functionality** (Create / Read / Update /
Delete) for two menu items: **Companies** and **Employees**.
6. Companies table consists of these fields: **Name (required), email, logo,
website.**
7. Employees table consists of these fields: **First name (required), last name
(required), Company (foreign key to Companies), email, phone**.
8. Use **database migrations** to create those schemas above.
9. Store company’s logos in **storage/app/public** folder and make them
accessible from public.
10. Use basic Laravel **resource controllers** with default methods.
11. Use **Laravel validation function** , using Request classes.


12. Use **Ant Design Vue Library** (Javscript UI library) and combine it with Tailwinds
    (Please refer to the official documentation for the library setup). Link to
    AntDesignVue : https://antdv.com/components/overview
13. Explore the datatable component from **Ant Design Vue 3** based on the
    documentation.
14. Use Laravel resource and Laravel collection class to format the API response.
15. Use **datatable component** from **Ant Design Vue**
    (https://antdv.com/components/table/#Table) to show
    Companies/Employees list using **server-side pagination**.
       o Companies datatable should have these as columns:
          **Index, Name, Email, Logo, Website** (clickable link) and **Action**.
       o Employees datatable should have these as columns:
          **Index, Full name, Company** (this is a clickable link which will show
          company details in a modal/window), **Email, Phone** and **Action**.
       o Each Action column must have **Edit** and **Delete** button:
          o When **Edit** button is clicked, **show modal** to edit details.
          o When **Delete** button is clicked, ask for **confirmation** before
             allowing deletion.

**Bonus Question:**

1. Use **Laravel Notifications** to send email notification to the company whenever
    a new employee is added.

**NOTE:** Once the test is completed, kindly upload your project to a public Bitbucket /
GitLab repository and provide us with the link for further evaluation.

Thank you, and all the best!


