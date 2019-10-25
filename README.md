# Skyline Modules
The modules package can be used to split a large application into smaller partitions.

#### What are modules?
Modules are directories containing information for compiling and delivering your contents.  

#### What does a module?
In the SkylineAppData you define templates, rendering, routing and component information.  
A module can specifically extend this information by module specific infos.

#### Global Structure
- ````SkylineAppData/````  
    Root directory of Skyline CMS contents.
    - ```Classes/```  
        PHP classes used as controllers or whatever.
    - ```Components/```  
        Components that the main layout render includes into the final page.
    - ```Templates/```  
        Template information
    - ``````
