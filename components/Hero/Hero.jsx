import { Link } from "react-router-dom";
import { Container } from "../Container";

const Hero = () => {
  return (
    <div className="bg-[#244034] ">
      <Container className="flex flex-wrap pt-40 ">
        <div className="flex items-center w-full lg:w-1/2">
          <div className="max-w-2xl mb-8">
            {/* <p className="bg-[#D6E264] rounded-full text-black inline p-3  uppercase">
              Event Management Platform
            </p> */}
            <h1 className="text-4xl font-bold leading-snug tracking-tight mt-5 text-gray-800 lg:text-4xl lg:leading-tight xl:text-6xl xl:leading-tight dark:text-white">
              Find Event Managers
            </h1>
            <h1 className="text-4xl font-bold leading-snug tracking-tight text-gray-800 lg:text-4xl lg:leading-tight xl:text-6xl xl:leading-tight dark:text-white">
              Or Become One
            </h1>

            <img src="/public/vector.svg" alt="" className="my-5" />
            <p className="text-lg leading-relaxed tracking-tight text-gray-800 lg:text-xl xl:text-2xl dark:text-white">
              Discover the perfect event manager to transform your ideas into
              unforgettable experiences, whether you're planning a wedding,
              corporate gathering, or private celebration. Alternatively, step
              into the spotlight and showcase your skills by becoming an event
              manager, connecting with clients who need your expertise to bring
              their visions to life.
            </p>
            <div className="flex flex-col items-start space-y-3 sm:space-x-4 sm:space-y-0 sm:items-center sm:flex-row mt-10">
              <Link
                to=""
                className="px-8 py-4 text-lg font-medium text-center text-white bg-[#96BB7C] rounded-md "
              >
                Hire a Planner
              </Link>
              <Link
                to="/signup"
                className="px-8 py-4 text-lg font-medium text-center text-white border rounded-md "
              >
                Join as a Planner
              </Link>
            </div>
          </div>
        </div>
        <div className="flex items-center justify-center w-full lg:w-1/2">
          <div className="">
            <img src="/public/hero.svg" alt="" />
          </div>
        </div>
      </Container>
    </div>
  );
};
export default Hero;
