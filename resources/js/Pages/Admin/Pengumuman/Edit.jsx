import React from "react";
import { Head, Link, useForm } from "@inertiajs/react";
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
    Textarea,
} from "@chakra-ui/react";
import { ArrowLeftIcon, BookmarkIcon } from "@heroicons/react/16/solid";
import AdminLayout from "../../../Layouts/AdminLayout";

const EditPengumuman = ({ auth, sessions, pengumuman }) => {
    const { data, setData, put, processing, errors } = useForm({
        judul: pengumuman.judul,
        deksripsi: pengumuman.deksripsi,
        tanggal: pengumuman.tanggal,
    });

    const submit = (e) => {
        e.preventDefault();
        put(`/pengumuman/${pengumuman.id}`);
    };

    return (
        <AdminLayout auth={auth} sessions={sessions}>
            <Head title="Edit Pengumuman" />
            <Card maxW={"xl"} w="full" p={2} h={"auto"}>
                <CardHeader pb={0}>
                    <Heading size="md" fontWeight="bold">
                        Edit Pengumuman
                    </Heading>
                </CardHeader>
                <form onSubmit={submit}>
                    <CardBody pb={0}>
                        <FormControl mb={3} isInvalid={errors.judul}>
                            <FormLabel htmlFor="judul" fontSize={"sm"}>
                                Judul
                                <Text display={"inline"} color="red">
                                    *
                                </Text>
                            </FormLabel>
                            <Input
                                type="text"
                                id="judul"
                                value={data.judul}
                                onChange={(e) =>
                                    setData("judul", e.target.value)
                                }
                            />
                            {errors.judul && (
                                <FormErrorMessage fontSize={"xs"}>
                                    {errors.judul}
                                </FormErrorMessage>
                            )}
                        </FormControl>
                        <FormControl mb={3} isInvalid={errors.deksripsi}>
                            <FormLabel htmlFor="deksripsi" fontSize={"sm"}>
                                Deksripsi
                                <Text display={"inline"} color="red">
                                    *
                                </Text>
                            </FormLabel>
                            <Textarea
                                id="deksripsi"
                                value={data.deksripsi}
                                onChange={(e) =>
                                    setData("deksripsi", e.target.value)
                                }
                            ></Textarea>
                            {errors.deksripsi && (
                                <FormErrorMessage fontSize={"xs"}>
                                    {errors.deksripsi}
                                </FormErrorMessage>
                            )}
                        </FormControl>
                        <FormControl mb={3} isInvalid={errors.tanggal}>
                            <FormLabel htmlFor="tanggal" fontSize={"sm"}>
                                Tanggal
                                <Text display={"inline"} color="red">
                                    *
                                </Text>
                            </FormLabel>
                            <Input
                                type="datetime-local"
                                id="tanggal"
                                value={data.tanggal}
                                onChange={(e) =>
                                    setData("tanggal", e.target.value)
                                }
                            />
                            {errors.tanggal && (
                                <FormErrorMessage fontSize={"xs"}>
                                    {errors.tanggal}
                                </FormErrorMessage>
                            )}
                        </FormControl>
                    </CardBody>
                    <CardFooter>
                        <Button
                            type="submit"
                            colorScheme="green"
                            isLoading={processing}
                            loadingText="Simpan"
                        >
                            <Icon as={BookmarkIcon} mr={2} />
                            Simpan
                        </Button>
                        <Button
                            as={Link}
                            href={"/pengumuman"}
                            colorScheme="gray"
                            ml={3}
                        >
                            <Icon as={ArrowLeftIcon} mr={2} />
                            Kembali
                        </Button>
                    </CardFooter>
                </form>
            </Card>
        </AdminLayout>
    );
};

export default EditPengumuman;
