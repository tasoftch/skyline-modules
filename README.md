# Skyline Modules
The modules package can be used to split a large application into smaller partitions.   

#### What are modules?
Modules are directories containing information for compiling and delivering your contents.  
There are no modules "available" in the application, because the modules specify additional information only.  
Each module must specify one or more deciders. A decider is asked, if a module should be selected under specific circumstances. The decider itself does not know, for which module it is deciding.  

#### What does a module?
In the SkylineAppData you define templates, rendering, routing and component information.  
A module can specifically extend this information by module specific infos.

#### Global Structure
- ````SkylineAppData/````  
    Root directory of Skyline CMS contents.
    - ```Classes/```  
        PHP classes used as controllers or only in the module.
    - ```Components/```  
        Components that the main layout render includes into the final page.
    - ```Config/```  
        Configurations
    - ```Templates/```  
        Template information

#### Declaring modules
You can declare any directory as a module if there is a file named ```module.cfg.php```.