
const Banner = () => {
    return (  
     <div className="bg-green-950 p-12 ">
      <div className="ml-20 mt-12 mb-8">
    <h1 className="text-3xl text-white font-bold mb-2">Find The Right <span className="bg-rose-700 rounded-xl p-1">Professionals</span></h1>
    <p className="text-white">From weddings to private parties and  corporate <br />events – connect  with experienced planners and <br /> vendors across all event types</p>
    </div>
   
    <div className="grid grid-cols-4 gap-2 mt-6 ml-14 ">
    <div className="card bg-base-100 w-64 shadow-xl ">
  <figure className="px-3 pt-2">
    <img
      src="/src/assets/stage.jpg"
      alt=""
      className="rounded-xl" />
  </figure>
  <div className="card-body items-center text-center">
    <h2 className="card-title">Wedding Planner</h2>
    <p><a href="">Top-20 planners available</a></p>
   
  </div>
</div>
 
      <div className="card bg-base-100 w-64 shadow-xl ">
  <figure className="px-3 pt-2">
    <img
      src="/src/assets/Wedding.jpg"
      alt=""
      className="rounded-xl" />
  </figure>
  <div className="card-body items-center text-center">
    <h2 className="card-title">Decorators</h2>
    <p><a href="">Top-20 planners available</a></p>
   
  </div>
</div>
      <div className="card bg-base-100 w-64 shadow-xl">
  <figure className="px-3 pt-2 ">
    <img
      src="/src/assets/Camera.jpg"
      alt=""
      className="rounded-xl w-72 h-40" />
  </figure>
  <div className="card-body items-center text-center">
    <h2 className="card-title">Photographers</h2>
    <p><a href="">Top-20 planners available</a></p>
   
  </div>
</div>
      <div className="card bg-amber-200 w-64 shadow-xl">
  <figure className="px-3 pt-2 ">
    <img
      src="/src/assets/cooks.jpg"
      alt="Shoes"
      className="rounded-xl  w-72 h-40 " />
  </figure>
  <div className="card-body items-center text-center">
    <h2 className="card-title">Caterings</h2>
    <p><a href="">Top-20 planners available</a></p>
   
  </div>
</div>
    </div>
 
     </div>
     
    );
};

export default Banner;