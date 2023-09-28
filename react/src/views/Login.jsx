import {Link} from "react-router-dom";
import {createRef, useState} from "react";
import axiosClient from "../axiosClient.js";
import {userStateContext} from "../contexts/ContextProvider";

export default function Login() {
  const emailRef = createRef()
  const passwordRef = createRef()
  const {setCurrentUser, setUserToken} = userStateContext()
  const [errors, setErrors] = useState(null)

  const onSubmit = ev => {
    ev.preventDefault()
    const payload = {
      email: emailRef.current.value,
      password: passwordRef.current.value,
    }

    axiosClient.post('/login', payload)
      .then(({data}) => {

         setCurrentUser(data.user)
         setUserToken(data.token);

      })
      .catch(error => {

        const response = error.response;

        if(error.response){
          Object.values(error.response.data.errors);
        }
        if (response && response.status === 422) {
          setErrors(response.data.errors)
        }
        if (response && response.status === 401) {
          
          router.navigate('/login');
          return error;
        }
      throw error;
      })
  }

  return (
    <>
      {/*
        This example requires updating your template:

        ```
        <html class="h-full bg-white">
        <body class="h-full">
        ```
      */}
		<h2 className="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
	            Sign in to your account
	          </h2>
      <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
        {errors &&
            <div className="alert bg-red-500 rounded py-2 px-3 text-white">
              {Object.keys(errors).map(key => (
                <p key={key}>{errors[key][0]}</p>
              ))}
            </div>
          }
          <form onSubmit={onSubmit} className="space-y-6" action="#" method="POST">
            <div>
              <label htmlFor="email" className="block text-sm font-medium leading-6 text-gray-900">
                Email address
              </label>
              <div className="mt-2">
                <input
                  id="email"
                  ref={emailRef}
                  type="email"
                  autoComplete="email"
                  required
                  className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            <div>
              <div className="flex items-center justify-between">
                <label htmlFor="password" className="block text-sm font-medium leading-6 text-gray-900">
                  Password
                </label>
                <div className="text-sm">
                  <a href="#" className="font-semibold text-indigo-600 hover:text-indigo-500">
                    Forgot password?
                  </a>
                </div>
              </div>
              <div className="mt-2">
                <input
                  id="password"
                   ref={passwordRef}
                  type="password"
                  autoComplete="current-password"
                  required
                  className="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                />
              </div>
            </div>

            <div>
              <button
                type="submit"
                className="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
              >
                Sign in
              </button>
            </div>
          </form>

          <p className="mt-10 text-center text-sm text-gray-500">
            Not a member?{' '}
            <a href="/signup" className="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">
              SignUp
            </a>
          </p>
        </div>
    </>
  )
}
