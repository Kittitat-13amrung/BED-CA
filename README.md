<p align="center"><img src="https://i.ibb.co/n1JhqNm/Youtube-Video.png" width="400" alt="Youtube Video Logo"></p>

## Project Overview

This Contionous Assessment is part of my Back-end Development assignment to develop a REST API of my choice. I have chose to do a Youtube Video API as it allows for the expandability and the complexity of relationships and functionalities of this case study.

In order to utilise my API to its utmost capacibilities. I have documented the basics API calls using [Swagger UI](https://swagger.io/tools/swagger-ui/) and examples of the resulting data you could expect when fetching through my API. 

**But in order to access the documentation itself** you must use: 

- ### ```npm install``` 

This will install the nescessarily dependencies to run Swagger. Then to access my documentation, <br/>
you can do so with ```{your domain}/api/documentation```

And when you have everything set up and run the server and access the site, it should return something like this:

<img src="https://user-images.githubusercontent.com/91548046/206813703-7d509fb1-adcd-4d81-a119-3e8f858fa7ee.png" width="900"/>

- ### Entity Relationships Diagram
<img src="https://iili.io/bMwy0X.png"/>

## Sample Data Retrieved Using Insomnia
<img src="https://user-images.githubusercontent.com/91548046/206813483-5fa502d4-18ba-43ba-961f-beae4ab9d47b.png" width="600"/>
<img src="https://user-images.githubusercontent.com/91548046/206813542-9e6efc2d-eee2-4433-9e26-444ed1ee2d4d.png" width="600"/>
<img src="https://user-images.githubusercontent.com/91548046/206813575-2ca519cf-0ee9-40a7-a8d6-a0805ad347f8.png" width="600"/>


### Featured I have implemented are:
1. Eagerloading relationships i.e. using the with() and whenLoaded() functionalities
2. The data retrieved from the API have all been implemented pagination to reduce retrieval time and for future users to used as infinite scrolls
3. Created components in Swagger so it could be easily reusable in other Controllers I have created.
4. Added extra API resources to retrieve data from the Id parameter i.e. getting all the comments from a specific video, gets all videos made by a specific channel etc.
5. Using each() functionality in Seeders to create nested factories to automatically assign the fake data with the channel that made those videos and populate the videos with comments.
6. Authentication using Sanctum
7. CRUD for all Videos, Comments, and Channel Controllers.
8. Policy and Request
9. Only channel that created those resources are allowed to update, delete them.
