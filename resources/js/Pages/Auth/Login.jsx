import React from "react";
import AuthLayout from "../../Layouts/AuthLayout";
import {
    Button,
    Card,
    CardBody,
    CardFooter,
    CardHeader,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Icon,
    Input,
    Text,
} from "@chakra-ui/react";
import { Head, useForm } from "@inertiajs/react";
import { ArrowLongRightIcon } from "@heroicons/react/16/solid";

const Login = ({ sessions }) => {
    const { data, setData, post, processing, errors } = useForm({
        email: "",
        password: "",
    });

    const submit = (e) => {
        e.preventDefault();
        post("/login");
    };

    return (
        <AuthLayout sessions={sessions}>
            <Head title="Login" />
            <Card maxW="md" w="full" p={2}>
                <CardHeader pb={0}>
                    <Heading size="md" fontWeight="bold" textAlign="center">
                        Login
                    </Heading>
                </CardHeader>
                <form onSubmit={submit}>
                    <CardBody pb={0}>
                        <FormControl mb={3} isInvalid={errors.email}>
                            <FormLabel htmlFor="email" fontSize={"sm"}>
                                Email{" "}
                                <Text display={"inline"} color="red">
                                    *
                                </Text>
                            </FormLabel>
                            <Input
                                type="email"
                                id="email"
                                value={data.email}
                                onChange={(e) =>
                                    setData("email", e.target.value)
                                }
                            />
                            {errors.email && (
                                <FormErrorMessage fontSize={"xs"}>
                                    {errors.email}
                                </FormErrorMessage>
                            )}
                        </FormControl>
                        <FormControl mb={3} isInvalid={errors.password}>
                            <FormLabel htmlFor="password" fontSize={"sm"}>
                                Password{" "}
                                <Text display={"inline"} color="red">
                                    *
                                </Text>
                            </FormLabel>
                            <Input
                                type="password"
                                id="password"
                                value={data.password}
                                onChange={(e) =>
                                    setData("password", e.target.value)
                                }
                            />
                            {errors.password && (
                                <FormErrorMessage fontSize={"xs"}>
                                    {errors.password}
                                </FormErrorMessage>
                            )}
                        </FormControl>
                    </CardBody>
                    <CardFooter
                        display={"flex"}
                        flexDirection={"column"}
                        gap={3}
                        justifyContent={"center"}
                    >
                        <Button
                            type="submit"
                            colorScheme="green"
                            w="100%"
                            isLoading={processing}
                            loadingText="Login"
                        >
                            <Icon as={ArrowLongRightIcon} mr={2} />
                            Login
                        </Button>
                    </CardFooter>
                </form>
            </Card>
        </AuthLayout>
    );
};

export default Login;
